<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    protected $server;
    protected $tokens;
    protected $jwt;

    public function __construct(AuthorizationServer $server,
                                TokenRepository $tokens,
                                JwtParser $jwt)
    {
        $this->jwt = $jwt;
        $this->server = $server;
        $this->tokens = $tokens;
    }

    public function login(ServerRequestInterface $request)
    {

        try {
            $request = $request->withParsedBody($request->getParsedBody() +
                [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'), //client id
                    'client_secret' => config('services.passport.client_secret'), //client secret
                ]);


            return with(new AccessTokenController($this->server, $this->tokens, $this->jwt))
                ->issueToken($request);

        } catch (OAuthServerException $e) {
            if ($e->getMessage() === 'The provided authorization grant (e.g., authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client.') {
                return response()->json(['status' => 'error', 'message' => 'Your credentials are incorrect. Please try again'], $e->statusCode());
            } else if ($e->getMessage() == 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed.') {
                return response()->json(['status' => 'error', 'message' => 'Invalid Request. Please enter a username or a password.'], $e->statusCode());
            }

            return response()->json('Something went wrong on the server.', $e->statusCode());
        }
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }
}
