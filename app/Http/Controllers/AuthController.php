<?php

namespace App\Http\Controllers;

use App\Models\LoginRequest;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

            $user = $request->user();
            if ( $user && ! $user->isApproved() ) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withInput()
                    ->withErrors( ['email' => 'Your account is pending admin approval.'] );
            }

            if ( $user && ! $this->isLoginApprovalValid( $user ) ) {
                $loginRequest = $this->createLoginRequestIfNeeded( $user, $request );
                $intended = $request->session()->get( 'url.intended' );
                $remember = $request->boolean( 'remember' );

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ( $intended ) {
                    $request->session()->put( 'url.intended', $intended );
                }

                $request->session()->put( 'pending_login_request_id', $loginRequest->id );
                $request->session()->put( 'pending_login_remember', $remember );

                return redirect()->route( 'login.waiting' );
            }

            if ( $user ) {
                UserActivityLog::create( [
                    'user_id' => $user->id,
                    'event' => 'login',
                    'method' => $request->method(),
                    'path' => '/login',
                    'route_name' => 'login',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ] );
            }

            return redirect()->intended( route( 'conveyances.create' ) );
        }

        return back()
            ->withInput()
            ->withErrors( ['email' => 'The provided credentials do not match our records.'] );
    }

    /**
     * Show waiting page for login approval.
     */
    public function showLoginWaiting( Request $request ) {
        $loginRequestId = (int) $request->session()->get( 'pending_login_request_id', 0 );
        if ( $loginRequestId <= 0 ) {
            return redirect()->route( 'login' );
        }

        $loginRequest = LoginRequest::with( 'user' )->find( $loginRequestId );
        if ( ! $loginRequest || $loginRequest->status === 'rejected' ) {
            $request->session()->forget( 'pending_login_request_id' );
            $request->session()->forget( 'pending_login_remember' );

            return redirect()
                ->route( 'login' )
                ->withErrors( ['email' => 'Login request was rejected. Please contact admin.'] );
        }

        return view( 'auth.login_waiting', [
            'loginRequest' => $loginRequest,
        ] );
    }

    /**
     * Poll login request approval status.
     */
    public function loginWaitingStatus( Request $request ) {
        $loginRequestId = (int) $request->session()->get( 'pending_login_request_id', 0 );
        if ( $loginRequestId <= 0 ) {
            return response()->json( ['status' => 'missing'] );
        }

        $loginRequest = LoginRequest::with( 'user' )->find( $loginRequestId );
        if ( ! $loginRequest ) {
            $request->session()->forget( 'pending_login_request_id' );
            $request->session()->forget( 'pending_login_remember' );

            return response()->json( ['status' => 'missing'] );
        }

        if ( $loginRequest->status === 'rejected' ) {
            $request->session()->forget( 'pending_login_request_id' );
            $request->session()->forget( 'pending_login_remember' );

            return response()->json( ['status' => 'rejected'] );
        }

        if ( $loginRequest->status === 'approved' ) {
            $user = $loginRequest->user;
            if ( ! $user || ! $user->isApproved() ) {
                $request->session()->forget( 'pending_login_request_id' );
                $request->session()->forget( 'pending_login_remember' );

                return response()->json( ['status' => 'blocked'] );
            }

            $remember = (bool) $request->session()->pull( 'pending_login_remember', false );

            Auth::login( $user, $remember );
            $request->session()->regenerate();
            $request->session()->forget( 'pending_login_request_id' );

            UserActivityLog::create( [
                'user_id' => $user->id,
                'event' => 'login',
                'method' => $request->method(),
                'path' => '/login/waiting',
                'route_name' => 'login.waiting.status',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ] );

            $redirect = $request->session()->pull( 'url.intended', route( 'conveyances.create' ) );

            return response()->json( [
                'status' => 'approved',
                'redirect' => $redirect,
            ] );
        }

        return response()->json( ['status' => 'pending'] );
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm() {
        session()->put( 'registration_started_at', now()->timestamp );

        return view( 'auth.register' );
    }

    /**
     * Handle registration.
     */
    public function register( Request $request ) {
        $email = strtolower( (string) $request->input( 'email' ) );
        $request->merge( ['email' => $email] );

        $this->ensureHumanRegistration( $request );

        $data = $request->validate( [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
                function ( $attribute, $value, $fail ) {
                    if ( ! str_ends_with( (string) $value, '@gmail.com' ) ) {
                        $fail( 'Only Gmail addresses are allowed.' );
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ] );

        /** @var \App\Models\User $user */
        $user = User::create( $data );

        return redirect()
            ->route( 'login' )
            ->with( 'status', 'Registration submitted. Please wait for admin approval.' );
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

    private function ensureHumanRegistration( Request $request ): void {
        if ( $request->filled( 'company' ) ) {
            throw ValidationException::withMessages( [
                'email' => 'Registration could not be completed. Please try again.',
            ] );
        }

        $startedAt = (int) $request->session()->pull( 'registration_started_at', 0 );
        $elapsed = $startedAt > 0 ? now()->timestamp - $startedAt : 0;

        if ( $elapsed < 3 ) {
            throw ValidationException::withMessages( [
                'email' => 'Registration could not be completed. Please try again.',
            ] );
        }
    }

    private function isLoginApprovalValid( User $user ): bool {
        if ( $user->is_admin ) {
            return true;
        }

        if ( $user->last_login_approved_at === null ) {
            return false;
        }

        return now()->lessThanOrEqualTo( $user->last_login_approved_at->copy()->addHours( 8 ) );
    }

    private function createLoginRequestIfNeeded( User $user, Request $request ): LoginRequest {
        $pending = LoginRequest::where( 'user_id', $user->id )
            ->where( 'status', 'pending' )
            ->first();

        if ( $pending ) {
            $pending->forceFill( [
                'request_ip' => $request->ip(),
                'request_user_agent' => $request->userAgent(),
            ] )->save();

            return $pending;
        }

        return LoginRequest::create( [
            'user_id' => $user->id,
            'status' => 'pending',
            'request_ip' => $request->ip(),
            'request_user_agent' => $request->userAgent(),
        ] );
    }
}
