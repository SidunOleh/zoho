<?php

namespace App\Services\Zoho;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class OAuth
{
    private string $baseUri;
    
    private string $clientId;

    private string $clientSecret;

    private string $redirectUri;

    private string $scope;

    public function __construct()
    {        
        $this->baseUri = config('services.zoho.domain') . '/oauth/v2';
        $this->clientId = config('services.zoho.client_id');
        $this->clientSecret = config('services.zoho.client_secret');
        $this->redirectUri = config('services.zoho.redirect_uri');
        $this->scope = config('services.zoho.scope');
    }

    public function authorizationRequest(): RedirectResponse
    {
        $query = http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'scope' => $this->scope,
            'access_type' => 'offline',
        ]);

        return redirect("{$this->baseUri}/auth?{$query}");
    }

    public function accessToken(string $code): array
    {
        $token = Http::asForm()->post("{$this->baseUri}/token", [
            'client_id' => $this->clientId,
            'grant_type' => 'authorization_code',
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
        ])->json();

        if (isset($response['error'])) {
            throw new Exception($response['error']);
        }

        $this->saveToken($token);

        return $token;
    }

    public function refreshToken(): array
    {
        $token = $this->getToken();

        $refreshed = Http::asForm()->post("{$this->baseUri}/token", [
            'client_id' => $this->clientId,
            'grant_type' => 'refresh_token',
            'client_secret' => $this->clientSecret,
            'refresh_token' => $token['refresh_token'],
        ])->json();

        if (isset($response['error'])) {
            throw new Exception($response['error']);
        }

        $this->saveToken($token = array_merge($token, $refreshed));

        return $token;
    }

    public function saveToken(array $token): void
    {
        session(['token' => $token,]);
    }

    public function getToken(): ?array
    {
        return session('token');
    }
}