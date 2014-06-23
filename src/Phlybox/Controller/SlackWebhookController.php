<?php

namespace Phlybox\Controller;

use Symfony\Component\HttpFoundation\Request;

class SlackWebhookController extends WebhookController
{
    public function upAction(Request $request)
    {
        $text = $request->get('text');
        list($trigger, $repositoryOwner, $repository, $baseBranch, $prNumber) = explode(' ', $text);

        $response = $this->up($repositoryOwner, $repository, $baseBranch, $prNumber);

        return $response->send();
    }

    public function currentAction(Request $request)
    {
        $text = $request->get('text');
        list($trigger) = explode(' ', $text);

        $response = $this->currently();

        return $response->send();
    }

    public function downAction(Request $request)
    {
        $text = $request->get('text');
        list($trigger, $boxId) = explode(' ', $text);

        $response = $this->down($boxId);

        return $response->send();
    }
}