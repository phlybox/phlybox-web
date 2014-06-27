<?php

namespace Phlybox\Controller;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Phlybox\Service\WebhookService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SlackWebhookController
{
    /** @var LoggerInterface */
    private $logger;
    private $webhookService;

    public function __construct()
    {
        $this->logger = new Logger('phlybox-web');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../../var/log/webhook.log'));

        $this->webhookService = new WebhookService($this->logger);
    }

    public function upAction(Request $request)
    {
        $text = $request->get('text');
        $this->logger->info("Received incoming Slack webhook with text: $text");
        list($trigger, $repositoryOwner, $repository, $baseBranch, $prNumber) = explode(' ', $text);

        $this->webhookService->up($repositoryOwner, $repository, $baseBranch, $prNumber);

        $response = new Response();
        $response->setContent("");

        return $response->send();
    }

    public function currentAction(Request $request)
    {
        $text = $request->get('text');
        $this->logger->info("Received incoming Slack webhook with text: $text");
        list($trigger) = explode(' ', $text);

        $this->webhookService->currently();

        $response = new Response();
        $response->setContent("");

        return $response->send();
    }

    public function downAction(Request $request)
    {
        $text = $request->get('text');
        $this->logger->info("Received incoming Slack webhook with text: $text");
        list($trigger, $boxId) = explode(' ', $text);

        $this->webhookService->down($boxId);

        $response = new Response();
        $response->setContent("");

        return $response->send();
    }
}