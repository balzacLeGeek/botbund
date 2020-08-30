<?php

namespace balzacLeGeek\BotbundBundle\Controller;

use balzacLeGeek\BotbundBundle\Webhooks\Webhooks;

use App\Bp\BotMessenger\Interact;
use App\Bp\BotMessenger\Profile;
use App\Bp\BotMessenger\ResponseFormat;
use balzacLeGeek\BotbundBundle\Webhooks\Hub;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/botbund/webhook")
 */
class BotbundController
{
    /** @var Webhooks $webhook */
    private $webhook;

    public function __construct(Webhooks $webhook)
    {
        $this->webhook = $webhook;
    }

    /**
     * Endpoint called from app settings (developer facebook) to enable webhooks integration
     * 
     * @Route("", name="botbund_webhook_verify", methods={"GET"})
     */
    public function webhookVerify(Request $request): Response
    {
        return $this->webhook->verify(Hub::buildFromRequest($request));
    }

    /**
     * Endpoint to receive requests from facebook messenger
     * 
     * @Route("", name="botbund_webhook_receive", methods={"POST"})
     */
    public function webhookResponse(Request $request, Interact $interact, ResponseFormat $responseFormat): JsonResponse
    {
        $dataQuery = $this->webhook->parsePayload($request);

        if ($dataQuery['object'] === Webhooks::PAGE_OBJECT) {

            foreach ($dataQuery['entry'] as $entry) {
                $webhookEvent = $entry['messaging'][0];
                $senderPsid = $webhookEvent['sender']['id'];

                // Message
                if(array_key_exists('postback', $webhookEvent)) {
                    if ($webhookEvent['postback']['payload'] === Profile::GET_STARTED) {
                        $interact->sendResponse($senderPsid, [
                            'text' => sprintf('Bienvenu sur iantsena!\nQue puis-je faire pour vous?'),
                        ]);
                    }

                }
                elseif (!array_key_exists('delivery', $webhookEvent) && !array_key_exists('read', $webhookEvent)) {
                    $userMessage = $webhookEvent['message'];

                    $interact->sendResponse($senderPsid, [
                        'text' => sprintf('Repy of "%s"', $userMessage['text']),
                    ]);
                    $interact->sendResponse($senderPsid, [
                        'text' => 'Want more?',
                    ]);
                }
            }
        }

        return $this->json([]);
    }

    /**
     * @Route("/test")
     */
    public function testResponseFormat(ResponseFormat $responseFormat)
    {
        $replyMessage = $responseFormat::text(['Hello', 'Ca va?']);

        dd($replyMessage['messages']);
    }
}
