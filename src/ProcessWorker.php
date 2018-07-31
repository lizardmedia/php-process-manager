<?php

namespace Phppm;

use Symfony\Component\Console\Output\OutputInterface;

class ProcessWorker
{
    /**
     * @var int
     */
    protected $interval;

    /**
     * @var ProcessInterface
     */
    public $process;

    /**
     * ProcessWorker constructor.
     *
     * @param string $process
     * @param int $interval
     */
    public function __construct($process, OutputInterface $output, int $interval = 100)
    {
        $this->process = new $process($output);
        $this->interval = $interval;
    }

    public function runProcess()
    {
        while (true) {
            $this->process->exec();
            usleep($this->interval * 1000);
        }
    }
}
