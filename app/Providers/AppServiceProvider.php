<?php

namespace App\Providers;

use App\Services\Zoho\OAuth;
use App\Services\Zoho\UnconnectedException;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Http::macro('zoho', function () {
            $oauth = new OAuth;
            $token = $oauth->getToken();

            if (! $token) {
                throw new UnconnectedException();
            }

            return Http::baseUrl($token['api_domain'])
                ->withToken($token['access_token'], 'Zoho-oauthtoken')
                ->retry(3, 0, function (Exception $exception, PendingRequest $request) use($oauth, $token) {
                    if (! $exception instanceof RequestException) {
                        return false;
                    }

                    $body = json_decode($exception->response->getBody()->getContents(), true);
                    $code = $body['code'] ?? '';

                    if ($code != 'INVALID_TOKEN') {
                        return false;
                    }

                    try {
                        $token = $oauth->refreshToken($token['refresh_token']);

                        $request->withToken($token['access_token'], 'Zoho-oauthtoken');

                        return true;
                    } catch (Exception $e) {
                        return false;
                    }
                });
        });
    }
}
