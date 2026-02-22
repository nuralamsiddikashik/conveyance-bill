<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    /**
     * Show the login form.
     */
    public function showLoginForm() {
        return view( 'auth.login' );
    }

    /**
     * Handle login.
     */
    public function login( Request $request ) {
        $credentials = $request->validate( [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ] );

        $remember = $request->boolean( 'remember' );

        if ( Auth::attempt( $credentials, $remember ) ) {
            $request->session()->regenerate();

            return redirect()->intended( route( 'conveyances.create' ) );
        }

        return back()
            ->withInput()
            ->withErrors( ['email' => 'The provided credentials do not match our records.'] );
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm() {
        return view( 'auth.register' );
    }

    /**
     * Handle registration.
     */
    public function register( Request $request ) {
        $data = $request->validate( [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ] );

        /** @var \App\Models\User $user */
        $user = User::create( $data );

        Auth::login( $user );

        $request->session()->regenerate();

        return redirect()->intended( route( 'conveyances.create' ) );
    }

    /**
     * Handle logout.
     */
    public function logout( Request $request ) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route( 'login' );
    }
}
