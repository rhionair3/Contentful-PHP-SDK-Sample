<?php

/**
 * @copyright   Denpasar RWDS 2018
 * @license     MIT
 * @author      Suryo Galih Kencana Harianja
 **/


namespace RWDSContentful;

use Contentful\Delivery\Client;
use function GuzzleHttp\json_encode;

require_once __DIR__ . '/vendor/autoload.php';


class CntentfulApi
{
    function __construct()
    {
        $tokenID = 'ey5b5nmpiokh';
        $spaceID = '6b55f9ebe7352c93f46a38da51216c7c987511ced19a82bc65eacfc02ea3d247';
        $envID = 'rwds-master';
        $options = [
            'baseUri' => $baseUri = null,
            'guzzle' => $guzzle = null,
            'logger' => $logger = null,
            'cache' => $cache = null,
            'autoWarmup' => $autoWarmup = false,
        ];

        $client = new \Contentful\Delivery\Client($accessToken, $spaceID, $environmentId, $defaultLocale = null, $options);

    }
}

?>