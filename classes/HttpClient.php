<?php namespace Inetis\GoogleCustomSearch\Classes;

use October\Rain\Network\Http;

/**
 * This plugin was originally using October's Http facade to make http requests to the Google API.
 * Using it was a bad idea as it was undocumented and it has some caveats (such as no SSL certificates verification).
 * With Laravel 9, a Http facade, which is a Guzzle wrapper, with a different interface has now taken the name
 * of October's client.
 * To maintain backward compatibility with OC 1 (which doesn't feature Guzzle) and not introduce a requirement on some
 * http library, here's a very minimalistic http helper based on cURL that allows to make a GET request.
 *
 * @see Http
 */
class HttpClient
{
    public $url;
    public $code;
    public $requestHeaders = [];
    public $requestData = [];
    public $responseBody = '';
    public $responseRawBody = '';

    public static function make($url)
    {
        $client = new self();
        $client->url = $url;

        return $client;
    }

    public function header($name, $value)
    {
        $this->requestHeaders[$name] = $value;

        return $this;
    }

    public function setData($data)
    {
        $this->requestData = $data;

        return $this;
    }

    public function addData($data)
    {
        $this->requestData = array_merge($this->requestData, $data);

        return $this;
    }

    public function send()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_URL, $this->getEncodedUrl());
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getEncodedRequestHeaders());

        $response = $this->responseRawBody = curl_exec($curl);
        $this->code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $this->responseBody = substr($response, $headerSize);

        curl_close($curl);

        return $this;
    }

    public function __toString()
    {
        return (string) $this->responseBody;
    }

    private function getEncodedUrl()
    {
        return $this->url . '?' . http_build_query($this->requestData);
    }

    private function getEncodedRequestHeaders()
    {
        $requestHeaders = [];

        foreach ($this->requestHeaders as $name => $value) {
            $requestHeaders[] = "{$name}: {$value}";
        }

        return $requestHeaders;
    }
}
