<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'ssoLogin']);
    }

    /**
     * Handle SSO login redirect
     */
    public function ssoLogin(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string',
            'state' => 'nullable|string',
        ]);

        // Check if client app exists
        $clientApp = \App\Models\ClientApp::where('client_id', $request->client_id)
            ->where('is_active', true)
            ->first();

        if (!$clientApp) {
            return redirect()->route('login')
                ->with('error', 'Aplikasi tidak terdaftar atau tidak aktif.');
        }

        // Store client info in session for later
        session(['sso_client_id' => $request->client_id]);
        session(['sso_state' => $request->state]);

        // If user is already logged in, redirect to authorize
        if (Auth::check()) {
            return redirect()->route('sso.authorize');
        }

        // Otherwise, show login form
        return redirect()->route('login')
            ->with('message', 'Silakan login untuk melanjutkan ke ' . $clientApp->name);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Check if login is part of SSO flow
        if (session('sso_client_id')) {
            return redirect()->route('sso.authorize');
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
}
