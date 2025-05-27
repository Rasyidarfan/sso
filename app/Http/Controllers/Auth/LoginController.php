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

        // If user is already logged in, redirect to process authorization with parameters
        if (Auth::check()) {
            $processUrl = route('sso.process') . '?' . http_build_query([
                'client_id' => $request->client_id,
                'state' => $request->state
            ]);
            return redirect($processUrl);
        }

        // Otherwise, show login form with SSO parameters
        $loginUrl = route('login') . '?' . http_build_query([
            'client_id' => $request->client_id,
            'state' => $request->state,
            'redirect_after_login' => 'sso_authorize'
        ]);
        
        return redirect($loginUrl)
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
        // Check if login is part of SSO flow by checking URL parameters
        if ($request->has('redirect_after_login') && $request->redirect_after_login === 'sso_authorize') {
            // Redirect to process authorization with SSO parameters
            $processParams = [];
            if ($request->has('client_id')) {
                $processParams['client_id'] = $request->client_id;
            }
            if ($request->has('state')) {
                $processParams['state'] = $request->state;
            }
            
            if (!empty($processParams)) {
                $processUrl = route('sso.process') . '?' . http_build_query($processParams);
                return redirect($processUrl);
            }
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
