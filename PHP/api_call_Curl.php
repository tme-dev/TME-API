<?php

/**
 * TME API - code snippet.
 * More info at: https://developers.tme.eu
 */
 
$token = '<put_your_token_here>';
$app_secret = '<put_your_app_secret_here>';

include('misising_functions.php');

$params = array(
  'SymbolList' => array('1N4007'),
  'Country' => 'PL',
  'Currency' => 'PLN',
  'Language' => 'PL',
);

$response = api_call('Products/GetPrices', $params, $token, $app_secret, true);
$result = json_decode($response, true);

print_r($result);


//---------------------------------------------------------
function api_call($action, $params, $token, $app_secret, $show_header=false)
{
    $api_url = 'https://api.tme.eu/' . $action . '.json';
    $curl = curl_init();

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
    //--

    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);


    $response = curl_exec($curl);

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    if($show_header){
        print_r($header);
    }

    curl_close($curl);
    
    return $body;
}
