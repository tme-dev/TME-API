<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

$token = '<put_your_token_here>';
$app_secret = '<put_your_app_secret_here>';

require 'vendor/autoload.php';

$credentials = new \TMEApiConnect\Hmac\Credentials($token, $app_secret);
$middleware = new \TMEApiConnect\Hmac\HmacSignMiddleware($credentials);

// Register the middleware.
$stack = HandlerStack::create();
$stack->push($middleware);

// Create a Guzzle client.
$client = new Client([
    'handler' => $stack,
]);

// Send request
$options = [
    'form_params' => [
        'SymbolList' => [
            'NE555D',
            '1N4007-DIO'
        ],
        'Country' => 'US',
        'Language' => 'EN',
    ],
];
$result = $client->request('POST', 'https://api.tme.eu/Products/GetProducts.json', $options);
print_r(json_decode($result->getBody(), true));