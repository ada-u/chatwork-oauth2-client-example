<?php

namespace App\Http\Controllers;

use ChatWork\OAuth2\Client\ChatWorkProvider;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;

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
                'access_token' => $accessToken->getToken(),
            ]
        ));

        $resourceOwner = $provider->getResourceOwner($accessToken);

        //if ($accessToken->hasExpired()) {
        $refreshedAccessToken = $provider->getAccessToken((string) new RefreshToken(), [
            'refresh_token' => $accessToken->getRefreshToken()
        ]);
        //}

        Log::debug(json_encode(
            [
                'resource_owner' => $resourceOwner->toArray()
            ]
        ));

        $client = new Client();
        $response = $client->request('GET', 'https://api.chatwork.com/v2/my/status', [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $accessToken)
            ]
        ]);

        return view('callback', [
            'resource_owner'         => $resourceOwner,
            'access_token'           => $accessToken->getToken(),
            'refreshed_access_token' => $refreshedAccessToken->getToken(),
            'state'                  => $state,
            'code'                   => $code,
            'error'                  => $error,
            'error_code'             => $errorCode,
            'error_description'      => $errorDescription
        ]);
    }
}
