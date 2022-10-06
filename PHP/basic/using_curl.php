<?php

/**
 * TME API - example usage with PHP & CURL.
 * More info at: https://developers.tme.eu
 */

const TOKEN = '<put_your_token_here>';
const APP_SECRET = '<put_your_app_secret_here>';


$params = array(
    'SymbolList' => array('1N4007-DC'),
    'Country'    => 'PL',
    'Currency'   => 'PLN',
    'Language'   => 'PL',
);

$response = api_call('Products/GetPrices', $params, true);
$result = json_decode($response, true);

print_r($result);

function api_call($action, array $params, $show_header = false)
{
    $params['Token'] = TOKEN;
    $params['ApiSignature'] = getSignature($action, $params, APP_SECRET);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, getUrl($action));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);

    $response = curl_exec($curl);

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    if ($show_header) {
        print_r($header);
    }

    curl_close($curl);

    return $body;
}

function getSignature($action, array $parameters, $appSecret)
{
    $parameters = sortSignatureParams($parameters);

    $queryString = http_build_query($parameters, null, '&', PHP_QUERY_RFC3986);
    $signatureBase = strtoupper('POST') .
        '&' . rawurlencode(getUrl($action)) . '&' . rawurlencode($queryString);

    return base64_encode(hash_hmac('sha1', $signatureBase, $appSecret, true));
}

function getUrl($action)
{
    return 'https://api.tme.eu/' . $action . '.json';
}

function sortSignatureParams(array $params)
{
    ksort($params);

    foreach ($params as &$value) {
        if (is_array($value)) {
            $value = sortSignatureParams($value);
        }
    }

    return $params;
}
