<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class ProductIndexController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function __invoke()
    {
        $zid = Http::withToken(auth()->user()->authorization)
            ->retry(3)
            ->timeout(60)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Accept-Language' => 'ar',
                'Role' => 'Manager',
                'store-id' => '736775',
            ])
            ->get('https://api.zid.sa/v1/products');

        return response()->json($zid->json());
    }

}
