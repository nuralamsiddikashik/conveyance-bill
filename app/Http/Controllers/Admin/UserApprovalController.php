<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserApprovalController extends Controller {
    public function index(): View {
        $users = User::orderByDesc( 'created_at' )->get();

        return view( 'admin.users', [
            'users' => $users,
        ] );
    }

    public function approve( User $user ): RedirectResponse {
        if ( $user->is_admin ) {
            return back()->withErrors( ['status' => 'Admin accounts are always approved.'] );
        }

        if ( $user->approved_at ) {
            return back()->with( 'status', 'User already approved.' );
        }

        $user->forceFill( [
            'approved_at' => now(),
        ] )->save();

        return back()->with( 'status', 'User approved successfully.' );
    }
}
