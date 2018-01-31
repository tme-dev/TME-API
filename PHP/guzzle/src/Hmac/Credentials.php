<?php

namespace TMEApiConnect\Hmac;

/**
 * For signing requests.
 */
class Credentials
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $secret;

    /**
     * Initializes the key with a key ID and key secret.
     *
     * @param string $token Application or private token
     * @param string $secret Application secret
     */
    public function __construct($token, $secret)
    {
        $this->token = $token;
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }
}
