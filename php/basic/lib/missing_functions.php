<?php

/**
 * hash_hmac function
 * @link http://php.net/manual/en/function.hash-hmac.php
 */
if (false === function_exists('hash_hmac')) {
    function hash_hmac($algo, $data, $key, $raw_output = false)
    {
        $algo = strtolower($algo);
        $pack = 'H' . strlen($algo('test'));
        $size = 64;
        $opad = str_repeat(chr(0x5C), $size);
        $ipad = str_repeat(chr(0x36), $size);

        if (strlen($key) > $size) {
            $key = str_pad(pack($pack, $algo($key)), $size, chr(0x00));
        } else {
            $key = str_pad($key, $size, chr(0x00));
        }

        for ($i = 0; $i < strlen($key) - 1; $i ++) {
            $opad[$i] = $opad[$i] ^ $key[$i];
            $ipad[$i] = $ipad[$i] ^ $key[$i];
        }

        $output = $algo($opad . pack($pack, $algo($ipad . $data)));

        return ($raw_output) ? pack($pack, $output) : $output;
    }
}
