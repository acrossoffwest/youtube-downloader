<?php


namespace App;

use GuzzleHttp\Client;

/**
 * Class LicenseAgreementService
 * @package App\Services
 */
class FileService
{
    /**
     * @param string $url
     * @param $filepath
     * @return bool
     */
    public function download(string $url, string $filepath, \Closure $progress = null)
    {
        $client = new Client([
            'base_uri' => '',
            'verify' => false,
            'sink' => $filepath,
            'headers' => ['Connection' => 'close'],
            'curl.options' => [
                'CURLOPT_RETURNTRANSFER' => true,
                'CURLOPT_FORBID_REUSE' => true,
                'CURLOPT_FRESH_CONNECT' => true,
                'CURLOPT_FILE' => $filepath
            ]
        ]);
        $response = $client->get($url, [
            'progress' => $progress,
            'read_timeout' => 60*60
        ]);

        logs()->info($response->getStatusCode());

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        return true;
    }
}
