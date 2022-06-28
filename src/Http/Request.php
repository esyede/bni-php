<?php

namespace Esyede\BNI\Http;

use Esyede\BNI\Helpers\Crypter;
use Esyede\BNI\Exceptions\InvalidBNIException;

class Request
{
    private static $config = [];

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
        if (! in_array(strtolower($method), ['get', 'post'])) {
            throw new InvalidBNIException('Only GET and POST request are supported with this library');
        }

        $endpoint = ('production' === $this->config('environment'))
            ? rtrim($this->config('url_production'), '/') . '/' . ltrim($endpoint, '/')
            : rtrim($this->config('url_development'), '/') . '/' . ltrim($endpoint, '/');

        $headers = [
            'Content-Type: application/json',
            'Accept-Encoding: gzip, deflate',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'Accept-Language: en-US,en;q=0.8,id;q=0.6',
        ];

        $options = [
            CURLOPT_URL => $endpoint,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HEADER => false,
            CURLOPT_VERBOSE => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36',
        ];

        if ('post' === $method) {
            $payloads = [
                'client_id' => $this->config('client_id'),
                'prefix' => $this->config('prefix'),
                'data' => Crypter::encrypt(
                    json_encode($payloads),
                    $this->config('client_id'),
                    $this->config('client_secret')),
            ];
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloads);
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            $payloads = [];
            curl_setopt($ch, CURLOPT_URL, $endpoint);
        }

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $results = curl_exec($ch);
        $error = curl_error($ch);
        $headers = curl_getinfo($ch);

        curl_close($ch);

        return json_encode(compact('results', 'payloads', 'headers', 'error'));
    }
}