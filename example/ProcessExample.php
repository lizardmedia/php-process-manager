<?php

namespace Foo;

use Phppm\Process;

class ProcessExample extends Process
{
    public function exec() : void
    {
        $time = $this->getFormattedTime();
        $this->output->writeln("[$time] heartbeat {$this->getPid()}");
    }
}
