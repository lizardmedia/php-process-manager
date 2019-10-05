<?php

namespace Phppm;

use Symfony\Component\Console\Output\OutputInterface;

interface ProcessInterface
{
    public function __construct(OutputInterface $output, $worker);

    public function exec();
}
