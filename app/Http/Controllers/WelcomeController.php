<?php

namespace App\Http\Controllers;

use ChatWork\OAuth2\Client\ChatWorkProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Grant\AuthorizationCode;

class WelcomeController extends BaseController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        $provider = new ChatWorkProvider(
            env('OAUTH2_CLIENT_ID'),
            env('OAUTH2_CLIENT_SECRET'),
            env('OAUTH2_REDIRECT_URI')
        );

        $url = $provider->getAuthorizationUrl([
            'scope' => ['users.all:read', 'rooms.all:read_write']
        ]);

        $provider->getState();

        return view('start', [
            'url' => $url,
        ]);
    }
}
