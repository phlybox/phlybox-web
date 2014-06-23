<?php

namespace Phlybox\Controller;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use pascaldevink\Phlybox\Service\GithubRepositoryService;
use pascaldevink\Phlybox\Service\SlackNotificationService;
use pascaldevink\Phlybox\Service\VagrantService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Process\Process;

abstract class WebhookController
{
    public abstract function upAction(Request $request);
    public abstract function currentAction(Request $request);
    public abstract function downAction(Request $request);

    protected function up($repositoryOwner, $repository, $baseBranch, $prNumber)
    {
        $processLine = "./phlybox.phar up $repositoryOwner $repository $baseBranch $prNumber";
        return $this->execute($processLine);
    }

    protected function currently()
    {
        $processLine = "./phlybox.phar current";
        return $this->execute($processLine);
    }

    protected function down($boxId)
    {
        $processLine = "./phlybox.phar down $boxId";
        return $this->execute($processLine);
    }

    /**
     * @param $processLine
     *
     * @return StreamedResponse
     */
    private function execute($processLine)
    {
        $process = new Process($processLine);
        $process->setTimeout(null);
        $process->setWorkingDirectory(__DIR__ . '/../../../data/');

        $response = new StreamedResponse();
        $response->setCallback(function () use ($process) {
            $process->run(function ($type, $buffer) {
                echo $buffer . '<br>';

                flush();
                ob_flush();
            });
        });

        return $response;
    }
}
