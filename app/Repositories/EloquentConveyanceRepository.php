<?php

namespace App\Repositories;

use App\Models\Conveyance;
use Illuminate\Support\Facades\DB;

class EloquentConveyanceRepository implements ConveyanceRepositoryInterface {
    public function createForDate( string $date, array $rows ): Conveyance {
        $total = 0;
        foreach ( $rows as $row ) {
            $total += (float) ( $row['amount'] ?? 0 );
        }

        DB::transaction( function () use ( $date, $rows, $total, &$conveyance ) {
            $conveyance = Conveyance::create( [
                'date'         => $date,
                'total_amount' => $total,
            ] );

            foreach ( $rows as $row ) {
                $conveyance->items()->create( [
                    'from_place' => $row['from'] ?? null,
                    'to_place'   => $row['to'] ?? null,
                    'amount'     => (float) ( $row['amount'] ?? 0 ),
                    'remarks'    => $row['remarks'] ?? null,
                ] );
            }
        } );

        return $conveyance;
    }

    public function all() {
        return Conveyance::orderByDesc( 'date' )->get();
    }

    public function findByDate( string $date ): ?Conveyance {
        return Conveyance::with( 'items' )->where( 'date', $date )->first();
    }

    public function delete( Conveyance $conveyance ): void {
        $conveyance->delete();
    }
}
