<?php

/**
 * TME API - code snippet.
 * More info at: https://developers.tme.eu
 */

include('misising_functions.php');

$token = '<put_your_token_here>';
$app_secret = '<put_your_app_secret_here>';

$params = array(
  'SymbolList' => array('1N4007'),
  'Country' => 'PL',
  'Currency' => 'PLN',
  'Language' => 'PL',
);

$response = api_call('Products/GetPrices', $params, $token, $app_secret, true);
$result = json_decode($response, true);

var_dump(PHP_VERSION);

echo '<pre>';
print_r($result);
echo '</pre>';


//---------------------------------------------------------
function api_call($action, $params, $token, $app_secret, $show_header=false)
{
    $api_url = 'https://api.tme.eu/' . $action . '.json';

    // calculate HMAC-SHA1 signature
    $params['Token'] = $token;
    ksort($params);

    // In PHP 5.4 http_build_query() offers enc_type parameter (PHP_QUERY_RFC3986) which replaces bellow snippet.
    $encoded_params = str_replace(
        array('+', '%7E'),
        array('%20', '~'),
        http_build_query($params)
    );
    
    $signature_base = 'POST' . '&' . rawurlencode($api_url) . '&' . rawurlencode($encoded_params);
    $api_signature  = base64_encode(hash_hmac('sha1', $signature_base, $app_secret, true));

    $params['ApiSignature'] = $api_signature;
  
  	$opts = array('http' =>
  		array(
  			'method'  => 'POST',
  			'header'  => 'Content-type: application/x-www-form-urlencoded',
  			'content' => http_build_query($params)
  		)
  	);
    
    return file_get_contents($api_url, false, stream_context_create($opts));
}