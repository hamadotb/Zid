<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CallbackController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function __invoke(Request $request)
    {
        $data = [
            'client_id' => config('services.zid.client_id'),
            'client_secret' => config('services.zid.client_secret'),
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'redirect_url' => config('services.zid.redirect_uri'),
            'state' => $request->state,
        ];

        $response = Http::asForm()->post(config('services.zid.token_url'), $data);

        if (!$response->successful()) {
            return redirect()
                ->route('dashboard')
                ->withErrors('Failed to retrieve access token');
        }

        $tokens = $response->json();
        $user = Auth::user()->update([
            'access_token' => $tokens['access_token'],
            'authorization' => $tokens['authorization'],
            'refresh_token' => $tokens['refresh_token'],
            'expires_at' => now()->addSeconds($tokens['expires_in']),
        ]);

        if (!$user) {
            return redirect()
                ->route('logout')
                ->withErrors('You need to log in again to connect your Zid account.');
        }

        return redirect()
            ->route('dashboard')
            ->with('status', 'Zid connected successfully!');
    }
}
