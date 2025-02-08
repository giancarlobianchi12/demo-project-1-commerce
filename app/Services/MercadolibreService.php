<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class MercadolibreService
{
    private string $apiUri;

    private string $clientId;

    private string $clientSecret;

    private string $redirectUri;

    public function __construct()
    {
        $this->apiUri = config('services.mercadolibre.api_uri');
        $this->clientId = config('services.mercadolibre.client_id');
        $this->clientSecret = config('services.mercadolibre.client_secret');
        $this->redirectUri = config('services.mercadolibre.redirect_uri');
    }

    private function request($method, $url, $user = null, $params = [])
    {
        try {
            $headers = [];
            if ($user) {
                $headers['Authorization'] = 'Bearer '.$user->external_access_token;
            }

            $response = Http::withHeaders($headers)->{$method}($this->apiUri.$url, $params)->throw();

            return $response->json();
        } catch (RequestException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getAccessToken($code)
    {
        return $this->request('post', '/oauth/token', null, [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
        ]);
    }

    public function refreshToken($refreshToken)
    {
        return $this->request('post', '/oauth/token', null, [
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
        ]);
    }

    public function getOrders($user, $from, $to, $limit = 51, $offset = 0)
    {
        return $this->request('get', '/orders/search', $user, [
            'order.status' => 'paid',
            'seller' => $user->external_id,
            'order.date_created.from' => $from,
            'order.date_created.to' => $to,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    public function getShipment($user, $shipmentId)
    {
        return $this->request('get', "/shipments/$shipmentId", $user);
    }

    public function getFromResource($user, $resource)
    {
        return $this->request('get', "/$resource", $user);
    }
}
