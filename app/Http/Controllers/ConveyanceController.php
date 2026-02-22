<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConveyanceRequest;
use App\Models\Conveyance;
use App\Models\ConveyanceItem;
use App\Repositories\ConveyanceRepositoryInterface;

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

        return view( 'conveyance', [
            'mode'       => 'create',
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

        $this->repo->createForDate( $validated['date'], $rows );

        return redirect()
            ->route( 'conveyances.create' )
            ->with( 'status', 'Conveyance saved successfully.' );
    }

    /**
     * List conveyances grouped by date.
     */
    public function index() {
        $conveyances = $this->repo->all();

        return view( 'conveyance_index', [
            'conveyances' => $conveyances,
        ] );
    }

    /**
     * Show a specific conveyance by its date.
     */
    public function showByDate( string $date ) {
        $conveyance = $this->repo->findByDate( $date ) ?? abort( 404 );

        $rows = $conveyance->items->map( function ( ConveyanceItem $item ) {
            return [
                'from'    => $item->from_place,
                'to'      => $item->to_place,
                'amount'  => (float) $item->amount,
                'remarks' => $item->remarks,
            ];
        } )->values()->all();

        return view( 'conveyance', [
            'mode'       => 'show',
            'conveyance' => $conveyance,
            'rows'       => $rows,
            'date'       => $conveyance->date->format( 'Y-m-d' ),
        ] );
    }

    /**
     * Show a specific conveyance by id.
     */
    public function show( Conveyance $conveyance ) {
        $conveyance->load( 'items' );

        $rows = $conveyance->items->map( function ( ConveyanceItem $item ) {
            return [
                'from'    => $item->from_place,
                'to'      => $item->to_place,
                'amount'  => (float) $item->amount,
                'remarks' => $item->remarks,
            ];
        } )->values()->all();

        return view( 'conveyance', [
            'mode'       => 'show',
            'conveyance' => $conveyance,
            'rows'       => $rows,
            'date'       => $conveyance->date->format( 'Y-m-d' ),
        ] );
    }

    /**
     * Delete a conveyance and its items.
     */
    public function destroy( Conveyance $conveyance ) {
        $this->repo->delete( $conveyance );

        return redirect()
            ->route( 'conveyances.index' )
            ->with( 'status', 'Conveyance deleted successfully.' );
    }
}
