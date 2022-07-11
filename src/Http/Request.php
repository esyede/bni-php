<?php

namespace Esyede\BNI\Http;

use Esyede\BNI\Helpers\Crypter;
use Esyede\BNI\Exceptions\InvalidBNIException;

class Request
{
    private static $config = [];

    private $method;
    private $payloads = [];
    private $options = [];
    private $headers = [
        'Content-Type: application/json',
        'Accept-Encoding: gzip, deflate',
        'Cache-Control: max-age=0',
        'Connection: keep-alive',
        'Accept-Language: en-US,en;q=0.8,id;q=0.6',
    ];
    private $userAgent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';

    public function __construct(array $config)
    {
        if (empty(static::$config)) {
            static::$config = $config;
        }
    }

    public function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return static::$config;
        }

        return isset(static::$config[$key]) ? static::$config[$key] : $default;
    }

    public function get($endpoint, array $payloads = [])
    {
        return $this->request('get', $endpoint, $payloads);
    }

    public function post($endpoint, array $payloads = [])
    {
        return $this->request('post', $endpoint, $payloads);
    }

    public function request($method, $endpoint, array $payloads = [])
    {
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->payloads = $payloads;

        if (! in_array(strtolower($this->method), ['get', 'post'])) {
            throw new InvalidBNIException('Only GET and POST request are supported with this library');
        }

        $this->endpoint = ('production' === $this->config('environment'))
            ? rtrim($this->config('url_production'), '/') . '/' . ltrim($this->endpoint, '/')
            : rtrim($this->config('url_development'), '/') . '/' . ltrim($this->endpoint, '/');

        $this->options = [
            CURLOPT_URL => $this->endpoint,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HEADER => false,
            CURLOPT_VERBOSE => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => $this->userAgent,
        ];

        $ch = curl_init();

        if ('post' === $this->method) {
            // print_r($this->payloads);
            $payloads = [
                'client_id' => $this->config('client_id'),
                'prefix' => $this->config('prefix'),
                'data' => Crypter::encrypt(
                    $this->payloads,
                    $this->config('client_id'),
                    $this->config('client_secret')
                ),
            ];

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payloads));
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            $this->payloads = [];
            curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        }

        curl_setopt_array($ch, $this->options);

        $results = curl_exec($ch);
        $error = curl_error($ch);
        $headers = curl_getinfo($ch);

        curl_close($ch);

        $raw_details = $this->getRawDetails();

        return json_encode(compact('results', 'payloads', 'raw_details'));
    }

    public function getRawDetails()
    {
        return [
            'method' => $this->method,
            'endpoint' => $this->endpoint,
            'payloads' => [
                'client_id' => $this->config('client_id'),
                'prefix' => $this->config('prefix'),
                'data' => $this->payloads,
            ],
            'headers' => $this->headers,
            'user_agent' => $this->userAgent,
        ];
    }
}