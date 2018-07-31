<?php

namespace Phppm;

use Symfony\Component\Console\Output\OutputInterface;

class SecondProcess implements ProcessInterface
{
    /**
     * @var OutputInterface 
     */
    protected $output;

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
        $this->output->writeln("[$time] heartbeattttt");
    }

    public function open()
    {

    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function terminate()
    {
        // TODO: Implement terminate() method.
    }

    public function status()
    {
        // TODO: Implement status() method.
    }
}
