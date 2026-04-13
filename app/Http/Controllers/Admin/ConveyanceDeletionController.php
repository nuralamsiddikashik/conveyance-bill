<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConveyanceDeleteRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConveyanceDeletionController extends Controller {
    public function index(): View {
        $pending = ConveyanceDeleteRequest::with( ['conveyance', 'requester', 'conveyanceOwner'] )
            ->where( 'status', 'pending' )
            ->orderByDesc( 'created_at' )
            ->get();

        $history = ConveyanceDeleteRequest::with( ['requester', 'conveyanceOwner', 'approver', 'rejecter'] )
            ->where( 'status', '!=', 'pending' )
            ->orderByDesc( 'updated_at' )
            ->limit( 50 )
            ->get();

        return view( 'admin.deletions', [
            'pending' => $pending,
            'history' => $history,
        ] );
    }

    public function approve( ConveyanceDeleteRequest $deleteRequest ): RedirectResponse {
        if ( $deleteRequest->status !== 'pending' ) {
            return back()->withErrors( ['status' => 'This request has already been processed.'] );
        }

        $deleteRequest->forceFill( [
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ] )->save();

        if ( $deleteRequest->conveyance ) {
            $deleteRequest->conveyance->delete();
        }

        return back()->with( 'status', 'Deletion approved and conveyance removed.' );
    }

    public function reject( ConveyanceDeleteRequest $deleteRequest ): RedirectResponse {
        if ( $deleteRequest->status !== 'pending' ) {
            return back()->withErrors( ['status' => 'This request has already been processed.'] );
        }

        $deleteRequest->forceFill( [
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
        ] )->save();

        return back()->with( 'status', 'Deletion request rejected.' );
    }
}
