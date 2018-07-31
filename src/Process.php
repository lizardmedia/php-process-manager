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
     * Process constructor.
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return string
     */
    protected function getFormattedTime() : string
    {
        $now = \DateTime::createFromFormat('U.u', microtime(true));
        return $now->format("Y-d-m H:i:s.u");
    }
}
