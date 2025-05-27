<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function __invoke()
    {
        $data = [
            'client_id' => config('services.zid.client_id'),
            'client_secret' => config('services.zid.client_secret'),
            'response_type' => 'code',
            'redirect_url' => config('services.zid.redirect_uri'),
            'scope' => 'offline_access',
            'state' => rand(99999999, 111111110),
        ];

        $query = http_build_query($data);

        return redirect(config('services.zid.authorize_url') . '?' . $query);
    }
}
