<?php

namespace balzacLeGeek\BotbundBundle\Webhooks;

use balzacLeGeek\BotbundBundle\Services\Logger\SLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Webhooks
{
    CONST PAGE_OBJECT       = 'page';
    CONST WEBHOOK_LOG_FILE  = 'botmessenger-webhook';

    /**
     * @var SLogger $logger
     */
    protected $logger;

    /**
     * @var string $pageAccessToken
     */
    protected $pageAccessToken;

    /**
     * @var string $pageVerifyToken
     */
    protected $pageVerifyToken;

    /**
     * BotMessenger Webhook constructor
     * 
     * @param SLogger $logger
     * @param array $botConfig
     */
    public function __construct(SLogger $logger, array $botConfig)
    {
        $this->pageAccessToken = $botConfig['access_token'];
        $this->pageVerifyToken = $botConfig['verify_token'];

        $this->logger = $logger;
    }

    /**
     * Verifies HUB_VERIFY_TOKEN from messenger webhooks
     * 
     * @param Request $request The query should contains hub_mode, hub_verify_token, hub_challenge
     * 
     * @return Response Status 200 with the request hub_challenge if hub_verify_token & hub_mode matches, 401 otherwise
     */
    public function verify(Request $request): Response
    {
        $queries = $request->query->all();

        $this->logger->writeLog('WEBHOOK_VERIFICATION_CALLED', self::WEBHOOK_LOG_FILE);

        $this->logger->writeLog(json_encode($queries), self::WEBHOOK_LOG_FILE, true);

        if (isset($queries['hub_mode']) && isset($queries['hub_verify_token']) && isset($queries['hub_challenge'])) {

            if ($queries['hub_mode'] === "subscribe" && $queries['hub_verify_token'] === $this->pageVerifyToken) {
                $this->logger->writeLog('WEBHOOK_VERIFIED', self::WEBHOOK_LOG_FILE, true);

                return new Response($queries['hub_challenge']);
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
