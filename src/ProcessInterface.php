<?php

namespace Phppm;

use Symfony\Component\Console\Output\OutputInterface;

interface ProcessInterface
{
    public function __construct(OutputInterface $output);

    public function exec();

    public function open();

    public function close();

    public function terminate();

    public function status();
}
