<?php
/**
 * TME API - example usage with PHP && file_get_contents function.
 * More info at: https://developers.tme.eu
 */

const TOKEN = '<put_your_token_here>';
const APP_SECRET = '<put_your_app_secret_here>';

$params = array(
    'SymbolList' => array('1N4007'),
    'Country'    => 'PL',
    'Currency'   => 'PLN',
    'Language'   => 'PL',
);

$response = api_call('Products/GetPrices', $params);
$result = json_decode($response, true);

echo '<pre>';
print_r($result);
echo '</pre>';


//---------------------------------------------------------
function api_call($action, array $params)
{
    $params['Token'] = TOKEN;
    $params['ApiSignature'] = getSignature($action, $params, APP_SECRET);

    $opts = array(
        'http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params),
            ),
    );

    return file_get_contents(getUrl($action), false, stream_context_create($opts));
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
