<?php
/**
 * User: SofWar (ya.sofwar@yandex.com)
 */

namespace SofWar\AbiosGaming;


use GuzzleHttp\Client;

class Api
{
    /**
     * AbiosGaming API URL
     *
     * @var string
     */
    private static $apiUrl = 'https://api.abiosgaming.com/v2/';

    /**
     * OAuth Client Id from AbiosGaming API
     *
     * @var string
     */
    private $clientId;

    /**
     * OAuth Client Secret from AbiosGaming API
     *
     * @var string
     */
    private $clientSecret;

    /**
     * The authentication token used to access all other endpoints.
     *
     * @var string
     */
    private $access_token;

    /**
     * Expired access token
     * @var string
     */
    private $expiredDateTime;

    /**
     * Guzzle Client
     *
     * @var Client
     */
    private $guzzleClient;


    private $cache;

    /**
     * Main Constructor for AbiosGaming API Class
     *
     * @param string $clientId OAuth Client ID
     * @param string $clientSecret OAuth Client Secret
     * @param Client|null $guzzle
     * @throws \InvalidArgumentException
     */
    public function __construct($clientId, $clientSecret, Client $guzzle = null)
    {

        if (empty($clientId)) {
            throw new \InvalidArgumentException('You need to pass an OAuth Client ID.');
        }

        if (empty($clientSecret)) {
            throw new \InvalidArgumentException('You need to pass an OAuth Client Secret.');
        }

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        if ($guzzle === null) {
            $this->guzzleClient = new Client(['base_uri' => $this->getApiUrl(), 'timeout' => 2]);
        } else {
            $this->guzzleClient = $guzzle;
        }

        $this->initAccessToken();
    }

    /**
     * Get API URL
     *
     * @return string
     */
    public function getApiUrl()
    {
        return self::$apiUrl;
    }

    /**
     * Get request access token
     */
    private function initAccessToken()
    {
        if ($this->access_token === null) {
            $this->access_token = $this->request('oauth/access_token', ['client_id' => $this->clientId, 'client_secret' => $this->clientSecret, 'grant_type' => 'client_credentials'], 'POST');
        }
    }

    /**
     * @param $url
     * @param array $args
     * @param string $method
     * @return mixed
     */
    public function request($url, array $args = [], $method = 'GET')
    {
        $request = new Request($this, self::$apiUrl . $url);

        if ($url === 'oauth/access_token') {
            $request->setArgs(['access_token' => $this->access_token]);
        }

        if (count($args)) {
            $request->setArgs($args);
        }

        return $request->send($method);
    }

    /**
     * Get client id
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Get client secret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Get Guzzle Client
     *
     * @return Client
     */
    public function getGuzzleClient()
    {
        return $this->guzzleClient;
    }

    /**
     * Get authentication token used to access all other endpoints.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }
}