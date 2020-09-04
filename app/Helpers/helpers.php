<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

if (!function_exists('generate_api_token')) {
    function generate_api_token(): string
    {
        if (auth()->guest()) {
            return '';
        }

        $tokenId = 'user-api-token-'.auth()->user()->id;
        $hashedToken = Cache::get($tokenId);
        if (!empty($hashedToken)) {
            return $hashedToken;
        }

        $token = Str::random(60);
        $hashedToken = hash('sha256', $token);
        auth()->user()->forceFill([
            'api_token' => $hashedToken,
            'api_token_updated_at' => \Carbon\Carbon::now()
        ])->save();
        Cache::put($tokenId, $hashedToken, 60);

        return $hashedToken;
    }
}
