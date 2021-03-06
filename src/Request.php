<?php
/**
 * User: SofWar (ya.sofwar@yandex.com)
 * Date: 26.07.2017 20:02
 */

namespace SofWar\AbiosGaming;


use GuzzleHttp\Exception\TransferException;

class Request
{
    /**
     * API Object Holder
     *
     * @var Object Api
     */
    private $api;

    /**
     * Final built URL for a request.
     *
     * @var string
     */
    private $url;

    /**
     * Individual components of the request url.
     *
     * @var array
     */
    private $args = [];

    /**
     * Main Constructor
     *
     * Request constructor.
     * @param Api $api
     * @param $url
     */
    public function __construct(Api $api, $url = null)
    {
        $this->api = $api;
        $this->url = $url;
    }

    /**
     * @param string $method
     * @return mixed
     * @throws ApiException
     */
    public function send($method = 'GET')
    {
        try {
            $method = strtoupper($method);

            if ($method === 'GET') {
                $result = $this->get();
            } else if ($method === 'POST') {
                $result = $this->post();
            } else {
                throw new \InvalidArgumentException('Wrong method specified.');
            }

        } catch (TransferException $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        }

        if ($result->getStatusCode() !== 200) {
            $respCode = $result->getStatusCode();
            $respBody = $result->getBody();
            throw new ApiException('AbiosGaming API returned ' . $respCode . ': ' . $respBody);
        }

        return json_decode($result->getBody()->getContents(), true);
    }

    /**
     * Performs a GET request to the remote API.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function get()
    {
        $url = $this->build_query_url();

        return $this->api->getGuzzleClient()->get($this->url, ['query' => $url]);
    }

    /**
     * @param bool $url
     * @return null|string
     */
    private function build_query_url($url = false)
    {
        $query = '';

        if ($url) {
            $query = $this->url;
        }


        if (count($this->args)) {
            $query .= '?';

            foreach ($this->args as $name => $value) {
                if (is_array($value)) {
                    $query .= $this->_build_query_url($name, $value);
                } else {
                    $query .= '&' . $name . '=' . $value;
                }
            }
        }

        return $query;
    }

    /**
     * Performs a POST request to the remote API.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function post()
    {
        return $this->api->getGuzzleClient()->post($this->url, ['form_params' => $this->args]);
    }

    /**
     * Set arguments to request.
     *
     * @param array $args All extra parameters for the API request.
     */
    public function setArgs($args = [])
    {
        $this->args = array_merge($this->args, $args);
    }

    /**
     * Clear param arsg
     */
    public function clearArgs()
    {
        $this->args = [];
    }

    /**
     * Set Method URL
     *
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Build query url
     *
     * @param $name
     * @param $args
     * @return string
     */
    private function _build_query_url($name, $args)
    {
        $_q = '';

        foreach ($args as $value) {
            if (is_array($value)) {
                $_q .= $this->_build_query_url($name, $value);
            } else {
                $_q .= '&' . $name . '[]=' . $value;
            }
        }

        return $_q;
    }
}