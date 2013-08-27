<?php
/**
 * TME API - code snippet.
 *
 * More info at: https://developers.tme.eu
 */
$token = '<put_your_token_here>';
$secret_key = '<put_your_secret_here>';
$params = array(
  'SymbolList' => array('1N4007'),
  'Country' => 'PL',
  'Currency' => 'PLN',
  'Language' => 'PL',
);

$response = api_call('Products/GetPrices', $params, $token, $secret_key);
$array = json_decode($response, true);

print_r($array);


//---------------------------------------------------------
function api_call($action, $params, $token, $secret_key)
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
    $api_signature  = base64_encode(hash_hmac('sha1', $signature_base, $secret_key, true));

    $params['ApiSignature'] = $api_signature;
    //--

    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

    $json_data = curl_exec($curl);
    curl_close($curl);
    
    return $json_data;
}
