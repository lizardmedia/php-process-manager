<?php

namespace Phppm;

use Symfony\Component\Console\Output\OutputInterface;

interface ProcessInterface
{
    public function __construct(OutputInterface $output, ProcessWorker $worker);

    public function exec();
}
