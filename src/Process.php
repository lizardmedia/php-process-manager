<?php

namespace Phppm;

use Symfony\Component\Console\Output\OutputInterface;

abstract class Process implements ProcessInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var ProcessWorker
     */
    protected $worker;

    /**
     * Process constructor.
     * @param OutputInterface $output
     * @param ProcessWorker $worker
     */
    public function __construct(OutputInterface $output, ProcessWorker $worker)
    {
        $this->worker = $worker;
        $this->output = $output;
    }

    /**
     * @return string
     */
    protected function getFormattedTime() : string
    {
        $now = \DateTime::createFromFormat('U.u', microtime(true));
        return $now->format('Y-d-m H:i:s.u');
    }

    /**
     * @return int
     */
    public function getPid() : int
    {
        return $this->worker->getPid();
    }

    /**
     * @return array
     */
    public function signalHandler() : array
    {
        return [];
    }
}
