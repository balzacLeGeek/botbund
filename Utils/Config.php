<?php

namespace balzacLeGeek\BotbundBundle\Utils;

class Config
{
    const FB_API_VERSION_KEY = 'fb_api_version';
    const ACCESS_TOKEN_KEY = 'access_token';
    const VERIFY_TOKEN_KEY = 'verify_token';

    const LOG_FILE = 'botbund.log';

    /** @var string $fbApiVersion */
    private $fbApiVersion;

    /** @var string $accessToken */
    private $accessToken;

    /** @var string $verifyToken */
    private $verifyToken;

    /**
     * @var string $fbApiVersion
     * @var string $accessToken
     * @var string $verifyToken
     */
    public function __construct(string $fbApiVersion, string $accessToken, string $verifyToken)
    {
        $this->fbApiVersion = $fbApiVersion;
        $this->accessToken = $accessToken;
        $this->verifyToken = $verifyToken;
    }

    /**
     * @return string
     */ 
    public function getFbApiVersion(): string
    {
        return $this->fbApiVersion;
    }

    /**
     * @return string
     */ 
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */ 
    public function getVerifyToken(): string
    {
        return $this->verifyToken;
    }
}
