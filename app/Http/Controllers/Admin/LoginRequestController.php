<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginRequestController extends Controller {
    public function index(): View {
        $pending = LoginRequest::with( ['user'] )
            ->where( 'status', 'pending' )
            ->orderByDesc( 'created_at' )
            ->get();

        $history = LoginRequest::with( ['user', 'approver', 'rejecter'] )
            ->where( 'status', '!=', 'pending' )
            ->orderByDesc( 'updated_at' )
            ->limit( 50 )
            ->get();

        return view( 'admin.login_requests', [
            'pending' => $pending,
            'history' => $history,
        ] );
    }

    public function approve( LoginRequest $loginRequest ): RedirectResponse {
        if ( $loginRequest->status !== 'pending' ) {
            return back()->withErrors( ['status' => 'This request has already been processed.'] );
        }

        $loginRequest->forceFill( [
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ] )->save();

        $user = $loginRequest->user;
        if ( $user ) {
            $user->forceFill( ['last_login_approved_at' => now()] )->save();
        }

        return back()->with( 'status', 'Login request approved.' );
    }

    public function reject( LoginRequest $loginRequest ): RedirectResponse {
        if ( $loginRequest->status !== 'pending' ) {
            return back()->withErrors( ['status' => 'This request has already been processed.'] );
        }

        $loginRequest->forceFill( [
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
        ] )->save();

        return back()->with( 'status', 'Login request rejected.' );
    }
}
