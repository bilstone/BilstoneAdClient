<?php

namespace Bilstone\AdClient;

/**
 * Class Client
 *
 * @package Bilstone\AdClient
 *
 * @author Mathieu Tanguay <mathieu.tanguay871@gmail.com>
 */
class AdClient
{
    /**
     * The host to connect to fetch the ads
     *
     * @var string
     */
    private $host;

    /**
     * The client key to identify yourself to the ad server
     *
     * @var string
     */
    private $clientKey;

    /**
     * Client constructor.
     *
     * @param $host
     * @param $clientKey
     */
    public function __construct($host, $clientKey)
    {
        $this->clientKey = $clientKey;
        $this->host = $host;
    }

    /**
     * Fetchs an ad to display
     *
     * @param $view string Chose which view to fetch
     * @return Ad
     */
    public function fetch($view)
    {
        $data = [
            'client_ip' => $this->getClientIp()
        ];

        $jsonAd = json_decode(file_get_contents(sprintf(
            "%s/ads/serve/%s/%s?%s",
            $this->host,
            $this->clientKey,
            $view,
            http_build_query($data)
        )), true);

        if (empty($jsonAd)) {
            return new Ad();
        }

        $ad = new Ad($jsonAd["content_with_wrapping"], $jsonAd["css"]);

        return $ad;
    }

    /**
     * @return string
     */
    protected function getClientIp()
    {
        $ip = 'unknown';

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}