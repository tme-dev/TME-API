<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

// Register your app here https://developers.tme.eu/en/dev
const TOKEN = '';
const SECRET = '';

require 'vendor/autoload.php';

$credentials = new \TMEApi\Hmac\Credentials(TOKEN, SECRET);
$middleware = new \TMEApi\Hmac\HmacSignMiddleware($credentials);

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
