<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConveyanceRequest;
use App\Models\Conveyance;
use App\Models\ConveyanceDeleteRequest;
use App\Models\ConveyanceItem;
use App\Repositories\ConveyanceRepositoryInterface;
use Illuminate\Http\Request;

class ConveyanceController extends Controller {
    private ConveyanceRepositoryInterface $repo;

    public function __construct( ConveyanceRepositoryInterface $repo ) {
        $this->repo = $repo;
    }

    /**
     * Show a fresh form for creating a conveyance.
     */
    public function create() {
        $today = now()->toDateString();

        return view( 'conveyances.create', [
            'conveyance' => null,
            'rows'       => [],
            'date'       => $today,
        ] );
    }

    /**
     * Store or update a conveyance for a given date.
     */
    public function store( StoreConveyanceRequest $request ) {
        $validated = $request->validated();

        $rows = $request->rows();

        if ( empty( $rows ) ) {
            return back()
                ->withInput()
                ->withErrors( ['rows' => 'Please add at least one conveyance row.'] );
        }

        $this->repo->createForDate( $request->user(), $validated['date'], $rows );

        return redirect()
            ->route( 'conveyances.create' )
            ->with( 'status', 'Conveyance saved successfully.' );
    }

    /**
     * Show the edit form for a conveyance.
     */
    public function edit( Conveyance $conveyance ) {
        $this->ensureAuthorized( $conveyance );
        $conveyance->load( 'items' );

        $rows = $this->rowsForConveyance( $conveyance );

        return view( 'conveyances.edit', [
            'conveyance' => $conveyance,
            'rows'       => $rows,
            'date'       => $conveyance->date->format( 'Y-m-d' ),
        ] );
    }

    /**
     * Update a conveyance and its items.
     */
    public function update( StoreConveyanceRequest $request, Conveyance $conveyance ) {
        $this->ensureAuthorized( $conveyance );
        $validated = $request->validated();

        $rows = $request->rows();

        if ( empty( $rows ) ) {
            return back()
                ->withInput()
                ->withErrors( ['rows' => 'Please add at least one conveyance row.'] );
        }

        $this->repo->update( $conveyance, $validated['date'], $rows );

        return redirect()
            ->route( 'conveyances.show', $conveyance )
            ->with( 'status', 'Conveyance updated successfully.' );
    }

    /**
     * List conveyances grouped by date.
     */
    public function index( Request $request ) {
        $filters = $request->validate( [
            'date_from'  => ['nullable', 'date'],
            'date_to'    => ['nullable', 'date', 'after_or_equal:date_from'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'gte:min_amount'],
            'user'       => ['nullable', 'string', 'max:255'],
        ] );

        $conveyances = $this->repo
            ->paginate( $request->user(), $filters, 10 )
            ->withQueryString();

        return view( 'conveyances.index', [
            'conveyances' => $conveyances,
            'filters'     => $filters,
        ] );
    }

    /**
     * Show a specific conveyance by its date.
     */
    public function showByDate( string $date ) {
        $conveyance = $this->repo->findByDate( auth()->user(), $date ) ?? abort( 404 );

        $rows = $this->rowsForConveyance( $conveyance );

        return view( 'conveyances.show', [
            'conveyance'  => $conveyance,
            'rows'        => $rows,
            'date'        => $conveyance->date->format( 'Y-m-d' ),
            'amountWords' => $this->amountToWords( (float) $conveyance->total_amount ),
        ] );
    }

    /**
     * Show a specific conveyance by id.
     */
    public function show( Conveyance $conveyance ) {
        $this->ensureAuthorized( $conveyance );
        $conveyance->load( 'items' );

        $rows = $this->rowsForConveyance( $conveyance );

        return view( 'conveyances.show', [
            'conveyance'  => $conveyance,
            'rows'        => $rows,
            'date'        => $conveyance->date->format( 'Y-m-d' ),
            'amountWords' => $this->amountToWords( (float) $conveyance->total_amount ),
        ] );
    }

    /**
     * Delete a conveyance and its items.
     */
    public function destroy( Conveyance $conveyance ) {
        $this->ensureAuthorized( $conveyance );
        $user = auth()->user();
        if ( $user && $user->is_admin ) {
            $this->repo->delete( $conveyance );

            return redirect()
                ->route( 'conveyances.index' )
                ->with( 'status', 'Conveyance deleted successfully.' );
        }

        $existing = ConveyanceDeleteRequest::where( 'conveyance_id', $conveyance->id )
            ->where( 'status', 'pending' )
            ->first();

        if ( $existing ) {
            return redirect()
                ->route( 'conveyances.index' )
                ->with( 'status', 'Deletion request already submitted and pending admin approval.' );
        }

        ConveyanceDeleteRequest::create( [
            'conveyance_id'           => $conveyance->id,
            'requested_by'            => $user?->id,
            'status'                  => 'pending',
            'conveyance_date'         => $conveyance->date,
            'conveyance_total_amount' => $conveyance->total_amount,
            'conveyance_owner_id'     => $conveyance->user_id,
            'request_ip'              => request()->ip(),
            'request_user_agent'      => request()->userAgent(),
        ] );

        return redirect()
            ->route( 'conveyances.index' )
            ->with( 'status', 'Deletion request sent to admin for approval.' );
    }

    private function ensureAuthorized( Conveyance $conveyance ): void {
        $user = auth()->user();

        if ( !$user ) {
            abort( 403 );
        }

        if ( $user->is_admin || $conveyance->user_id === $user->id ) {
            return;
        }

        abort( 403 );
    }

    private function rowsForConveyance( Conveyance $conveyance ): array {
        return $conveyance->items->map( function ( ConveyanceItem $item ) {
            return [
                'from'    => $item->from_place,
                'to'      => $item->to_place,
                'amount'  => (float) $item->amount,
                'remarks' => $item->remarks,
            ];
        } )->values()->all();
    }

    private function amountToWords( float $amount ): string {
        if ( $amount <= 0 ) {
            return 'Zero';
        }

        $ones = [
            '',
            'One',
            'Two',
            'Three',
            'Four',
            'Five',
            'Six',
            'Seven',
            'Eight',
            'Nine',
            'Ten',
            'Eleven',
            'Twelve',
            'Thirteen',
            'Fourteen',
            'Fifteen',
            'Sixteen',
            'Seventeen',
            'Eighteen',
            'Nineteen',
        ];

        $tens = [
            '',
            '',
            'Twenty',
            'Thirty',
            'Forty',
            'Fifty',
            'Sixty',
            'Seventy',
            'Eighty',
            'Ninety',
        ];

        $convert = function ( int $number ) use ( &$convert, $ones, $tens ): string {
            if ( $number < 20 ) {
                return $ones[$number];
            }

            if ( $number < 100 ) {
                return $tens[intdiv( $number, 10 )] . ( $number % 10 ? ' ' . $ones[$number % 10] : '' );
            }

            return $ones[intdiv( $number, 100 )] . ' Hundred' . ( $number % 100 ? ' ' . $convert( $number % 100 ) : '' );
        };

        $integerPart = (int) floor( $amount );
        $words       = '';

        if ( $integerPart >= 10000000 ) {
            $words .= $this->amountToWords( intdiv( $integerPart, 10000000 ) ) . ' Crore ';
            $integerPart %= 10000000;
        }

        if ( $integerPart >= 100000 ) {
            $words .= $this->amountToWords( intdiv( $integerPart, 100000 ) ) . ' Lakh ';
            $integerPart %= 100000;
        }

        if ( $integerPart >= 1000 ) {
            $words .= $convert( intdiv( $integerPart, 1000 ) ) . ' Thousand ';
            $integerPart %= 1000;
        }

        if ( $integerPart > 0 ) {
            $words .= $convert( $integerPart );
        }

        return trim( $words );
    }
}
