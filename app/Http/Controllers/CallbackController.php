<?php

namespace App\Http\Controllers;

use ChatWork\OAuth2\Client\ChatWorkProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Grant\AuthorizationCode;

class CallbackController extends BaseController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        $state = $request->input('state');
        $code = $request->input('code');
        $error = $request->input('error');
        $errorCode = $request->input('error_code');
        $errorDescription = $request->input('error_description');

        Log::debug(json_encode(
            [
                'state'             => $state,
                'code'              => $code,
                'error'             => $error,
                'error_code'        => $errorCode,
                'error_description' => $errorDescription
            ]
        ));

        $provider = new ChatWorkProvider(
            env('OAUTH2_CLIENT_ID'),
            env('OAUTH2_CLIENT_SECRET'),
            env('OAUTH2_REDIRECT_URI')
        );

        $accessToken = $provider->getAccessToken((string) new AuthorizationCode(), [
            'code' => $code
        ]);

        Log::debug(json_encode(
            [
                'access_token' => $accessToken,
            ]
        ));

        $resource_owner = $provider->getResourceOwner($accessToken);

        Log::debug(json_encode(
            [
                'resource_owner' => $resource_owner->toArray()
            ]
        ));

        return view('callback', [
            'resource_owner'    => $resource_owner,
            'access_token'      => $accessToken,
            'state'             => $state,
            'code'              => $code,
            'error'             => $error,
            'error_code'        => $errorCode,
            'error_description' => $errorDescription
        ]);
    }
}
