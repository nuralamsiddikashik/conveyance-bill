<?php

namespace App\Repositories;

use App\Models\Conveyance;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EloquentConveyanceRepository implements ConveyanceRepositoryInterface {
    public function createForDate( User $user, string $date, array $rows ): Conveyance {
        $total = 0;
        foreach ( $rows as $row ) {
            $total += (float) ( $row['amount'] ?? 0 );
        }

        DB::transaction( function () use ( $user, $date, $rows, $total, &$conveyance ) {
            $conveyance = Conveyance::create( [
                'user_id'      => $user->id,
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

    public function update( Conveyance $conveyance, string $date, array $rows ): Conveyance {
        $total = 0;
        foreach ( $rows as $row ) {
            $total += (float) ( $row['amount'] ?? 0 );
        }

        DB::transaction( function () use ( $conveyance, $date, $rows, $total ) {
            $conveyance->update( [
                'date'         => $date,
                'total_amount' => $total,
            ] );

            $conveyance->items()->delete();

            foreach ( $rows as $row ) {
                $conveyance->items()->create( [
                    'from_place' => $row['from'] ?? null,
                    'to_place'   => $row['to'] ?? null,
                    'amount'     => (float) ( $row['amount'] ?? 0 ),
                    'remarks'    => $row['remarks'] ?? null,
                ] );
            }
        } );

        return $conveyance->fresh( 'items' );
    }

    public function paginate( User $user, array $filters = [], int $perPage = 10 ): LengthAwarePaginator {
        $query = $this->queryForUser( $user )
            ->with( 'user' )
            ->withCount( 'items' );

        if ( ! empty( $filters['date_from'] ) ) {
            $query->whereDate( 'date', '>=', $filters['date_from'] );
        }

        if ( ! empty( $filters['date_to'] ) ) {
            $query->whereDate( 'date', '<=', $filters['date_to'] );
        }

        if ( isset( $filters['min_amount'] ) && $filters['min_amount'] !== '' ) {
            $query->where( 'total_amount', '>=', $filters['min_amount'] );
        }

        if ( isset( $filters['max_amount'] ) && $filters['max_amount'] !== '' ) {
            $query->where( 'total_amount', '<=', $filters['max_amount'] );
        }

        if ( $user->is_admin && ! empty( $filters['user'] ) ) {
            $search = trim( (string) $filters['user'] );
            $query->whereHas( 'user', function ( $userQuery ) use ( $search ) {
                $userQuery->where( 'name', 'like', "%{$search}%" )
                    ->orWhere( 'email', 'like', "%{$search}%" );
            } );
        }

        return $query
            ->orderByDesc( 'date' )
            ->orderByDesc( 'created_at' )
            ->paginate( $perPage );
    }

    public function findByDate( User $user, string $date ): ?Conveyance {
        return $this->queryForUser( $user )
            ->with( ['items', 'user'] )
            ->where( 'date', $date )
            ->orderByDesc( 'created_at' )
            ->first();
    }

    public function delete( Conveyance $conveyance ): void {
        $conveyance->delete();
    }

    private function queryForUser( User $user ) {
        $query = Conveyance::query();

        if ( ! $user->is_admin ) {
            $query->where( 'user_id', $user->id );
        }

        return $query;
    }
}
