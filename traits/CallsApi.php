<?php

namespace Sixgweb\ClubReady\Traits;

use ApplicationException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Sixgweb\ClubReady\Models\Setting as ClubReadySetting;

trait CallsApi
{
    public function getApiUrl($path = ''): string
    {
        $url = ClubReadySetting::get('api_endpoint');
        return str_replace('//', '/', $url . $path);
    }

    private function getApiResponse($path, $data = null, $method = null)
    {
        $method = $method ?? ($data ? 'POST' : 'GET');
        $query = [
            'ApiKey' => ClubReadySetting::get('api_key'),
            'StoreId' => (int) ClubReadySetting::get('store_id'),
        ];

        if ($data) {
            $query['json'] = $data;
        }

        $options = [
            'query' => $query,
        ];

        $client = new Client;

        try {
            $response = $client->request($method, $this->getApiUrl($path), $options);
        } catch (ClientException $e) {
            throw new ApplicationException('Error getting ' . $path . ': ' . $e->getMessage());
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
