<?php

namespace TMEApi\Hmac;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestSigner
{
    /**
     * @var Credentials
     */
    protected $credentials;

    /**
     * @param Credentials $credentials
     */
    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function signRequest(RequestInterface $request)
    {
        $requestParameters = [];
        parse_str(urldecode($request->getBody()->__toString()), $requestParameters);

        $credentialsParameters = [
            'Token'        => $this->credentials->getToken(),
            'ApiSignature' => $this->getSignature(
                $request->getUri(),
                array_merge(
                    $requestParameters,
                    [
                        'Token' => $this->credentials->getToken(),
                    ]
                )
            ),
        ];

        $request->getBody()->write('&' . http_build_query($credentialsParameters));

        return $request;
    }

    /**
     * Calculate signature
     *
     * @param UriInterface $requestUri
     * @param array $parameters
     *
     * @return string
     */
    private function getSignature(UriInterface $requestUri, array $parameters)
    {
        $parameters = $this->sortSignatureParams($parameters);

        $queryString = http_build_query($parameters, null, '&', PHP_QUERY_RFC3986);
        $signatureBase = strtoupper('POST') .
            '&' . rawurlencode($this->getUrl($requestUri)) . '&' . rawurlencode($queryString);

        return base64_encode(hash_hmac('sha1', $signatureBase, $this->credentials->getSecret(), true));
    }

    /**
     * @param \Psr\Http\Message\UriInterface $requestUri
     *
     * @return string
     */
    private function getUrl(UriInterface $requestUri)
    {
        return $requestUri->getScheme() . '://' . $requestUri->getHost() . $requestUri->getPath();
    }

    /**
     * Sort signature params recursively.
     *
     * @param array $params
     * @return array
     */
    private function sortSignatureParams(array $params)
    {
        ksort($params);

        foreach ($params as &$value) {
            if (is_array($value)) {
                $value = $this->sortSignatureParams($value);
            }
        }

        return $params;
    }
}
