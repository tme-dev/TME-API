<?php

namespace TMEApi\Hmac;

use Psr\Http\Message\RequestInterface;

class HmacSignMiddleware
{
    /**
     * @var \TMEApi\Hmac\RequestSigner
     */
    private $requestSigner;

    /**
     * @param Credentials $credentials
     */
    public function __construct(Credentials $credentials)
    {
        $this->requestSigner = new RequestSigner($credentials);
    }

    /**
     * Called when the middleware is handled.
     *
     * @param callable $handler
     *
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function ($request, array $options) use ($handler) {
            $request = $this->signRequest($request);
            return $handler($request, $options);
        };
    }

    /**
     * Signs the request
     * 
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function signRequest(RequestInterface $request)
    {
        return $this->requestSigner->signRequest($request);
    }
}
