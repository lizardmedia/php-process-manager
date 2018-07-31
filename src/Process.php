<?php

namespace Phppm;

use Symfony\Component\Console\Output\OutputInterface;

class Process implements ProcessInterface
{
    /**
     * @var OutputInterface 
     */
    protected $output;

    private $process;
    /**
     * Process constructor.
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function exec()
    {

        $now = \DateTime::createFromFormat('U.u', microtime(true));
        $time = $now->format("Y-d-m H:i:s.u");
        $this->output->writeln("[$time] heartbeat");
    }

}
