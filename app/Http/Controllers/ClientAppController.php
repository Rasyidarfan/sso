<?php

namespace App\Http\Controllers;

use App\Models\ClientApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAppController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Semua method hanya bisa diakses oleh admin
    }

    /**
     * Display a listing of the client applications.
     */
    public function index()
    {
        $clientApps = ClientApp::all();
        return view('client_apps.index', compact('clientApps'));
    }

    /**
     * Show the form for creating a new client application.
     */
    public function create()
    {
        return view('client_apps.create');
    }

    /**
     * Store a newly created client application in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'redirect_uri' => 'required|url',
            'description' => 'nullable|string',
        ]);

        // Generate client ID and secret
        $credentials = ClientApp::generateCredentials();

        $clientApp = ClientApp::create([
            'name' => $request->name,
            'redirect_uri' => $request->redirect_uri,
            'description' => $request->description,
            'client_id' => $credentials['client_id'],
            'client_secret' => $credentials['client_secret'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('client-apps.show', $clientApp)
            ->with('success', 'Aplikasi berhasil ditambahkan. Simpan Client ID dan Secret dengan aman!');
    }

    /**
     * Display the specified client application.
     */
    public function show(ClientApp $clientApp)
    {
        return view('client_apps.show', compact('clientApp'));
    }

    /**
     * Show the form for editing the specified client application.
     */
    public function edit(ClientApp $clientApp)
    {
        return view('client_apps.edit', compact('clientApp'));
    }

    /**
     * Update the specified client application in storage.
     */
    public function update(Request $request, ClientApp $clientApp)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'redirect_uri' => 'required|url',
            'description' => 'nullable|string',
        ]);

        $clientApp->update([
            'name' => $request->name,
            'redirect_uri' => $request->redirect_uri,
            'description' => $request->description,
        ]);

        return redirect()->route('client-apps.index')
            ->with('success', 'Aplikasi berhasil diperbarui.');
    }

    /**
     * Remove the specified client application from storage.
     */
    public function destroy(ClientApp $clientApp)
    {
        $clientApp->delete();

        return redirect()->route('client-apps.index')
            ->with('success', 'Aplikasi berhasil dihapus.');
    }

    /**
     * Toggle the active status of a client application.
     */
    public function toggleStatus(ClientApp $clientApp)
    {
        $clientApp->is_active = !$clientApp->is_active;
        $clientApp->save();

        $status = $clientApp->is_active ? 'aktif' : 'nonaktif';
        return redirect()->route('client-apps.index')
            ->with('success', "Aplikasi berhasil diubah menjadi {$status}.");
    }

    /**
     * Regenerate client secret.
     */
    public function regenerateSecret(ClientApp $clientApp)
    {
        $credentials = ClientApp::generateCredentials();
        
        $clientApp->client_secret = $credentials['client_secret'];
        $clientApp->save();

        return redirect()->route('client-apps.show', $clientApp)
            ->with('success', 'Client Secret berhasil di-regenerate. Simpan dengan aman!');
    }
}
