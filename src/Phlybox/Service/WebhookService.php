<?php

namespace Phlybox\Service;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class WebhookService
{
    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $repositoryOwner
     * @param $repository
     * @param $baseBranch
     * @param $prNumber
     */
    public function up($repositoryOwner, $repository, $baseBranch, $prNumber)
    {
        $processLine = "./phlybox.phar up $repositoryOwner $repository $baseBranch $prNumber";
        $this->logger->info("Executing: $processLine");
        $this->execute($processLine);
    }

    public function currently()
    {
        $processLine = "./phlybox.phar current";
        $this->logger->info("Executing: $processLine");
        $this->execute($processLine);
    }

    public function down($boxId)
    {
        $processLine = "./phlybox.phar down $boxId";
        $this->logger->info("Executing: $processLine");
        $this->execute($processLine);
    }

    /**
     * @param $processLine
     *
     * @return void
     */
    protected function execute($processLine)
    {
        $process = new Process($processLine);
        $process->setTimeout(null);
        $process->setWorkingDirectory(__DIR__ . '/../../../data/');
        $process->setIdleTimeout(60);

        $process->run(function ($type, $buffer) {
            $this->logger->debug($buffer);
        });
    }
} 