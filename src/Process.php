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
     * @var int
     */
    protected $pid;

    /**
     * Process constructor.
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output, $worker)
    {
        $this->output = $output;
        $this->pid = getmypid();
        $this->worker = $worker;
    }

    /**
     * @return string
     */
    protected function getFormattedTime() : string
    {
        $now = \DateTime::createFromFormat('U.u', microtime(true));
        return $now->format("Y-d-m H:i:s.u");
    }

    /**
     * @return int
     */
    public function getPid() : int
    {
        return $this->pid;
    }
}
