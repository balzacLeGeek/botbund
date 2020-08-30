<?php

namespace balzacLeGeek\BotbundBundle\Webhooks;

use balzacLeGeek\BotbundBundle\Services\Logger\SLogger;
use balzacLeGeek\BotbundBundle\Utils\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Webhooks
{
    CONST PAGE_OBJECT = 'page';
    CONST WEBHOOK_LOG_FILE = 'botmessenger-webhook';

    /**
     * @var SLogger $logger
     */
    protected $logger;

    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var string $pageAccessToken
     */
    protected $pageAccessToken;

    /**
     * @var string $pageVerifyToken
     */
    protected $pageVerifyToken;

    /**
     * @param SLogger $logger
     * @param Config $config
     */
    public function __construct(SLogger $logger, Config $config)
    {
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Verifies HUB_VERIFY_TOKEN from messenger webhooks
     * 
     * @param Request $request The query should contains hub_mode, hub_verify_token, hub_challenge
     * 
     * @return Response Status 200 with the request hub_challenge if hub_verify_token & hub_mode matches, 401 otherwise
     */
    public function verify(Hub $hub): Response
    {
        $this->logger->writeLog('WEBHOOK_VERIFICATION_CALLED', self::WEBHOOK_LOG_FILE);

        $this->logger->writeLog(json_encode($hub->getQueries()), self::WEBHOOK_LOG_FILE, true);

        if ($hub->hasMode() && $hub->hasVerifyToken() && $hub->hasChallenge()) {

            if ($hub->getMode() === "subscribe" && $hub->getVerifyToken() === $this->config->getVerifyToken()) {
                $this->logger->writeLog('WEBHOOK_VERIFIED', self::WEBHOOK_LOG_FILE, true);

                return new Response($hub->getChallenge());
            }

            $this->logger->writeLog('WRONG_WEBHOOK_TOKEN', self::WEBHOOK_LOG_FILE, true);

            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        $this->logger->writeLog('INVALID_WEBHOOK_CALL', self::WEBHOOK_LOG_FILE, true);

        return new Response('', Response::HTTP_FORBIDDEN);
    }

    /**
     * Parses datas from facebook webhook
     * 
     * @param Request $request
     * @return array
     */
    public function parsePayload(Request $request): ?array
    {
        $JSONQuery = $request->getContent();

        // Write request in Log file
        $this->logger->writeLog($JSONQuery, self::WEBHOOK_LOG_FILE);

        return json_decode($JSONQuery, true);
    }
}
