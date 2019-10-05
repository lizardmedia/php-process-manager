<?php

namespace Foo;

use Phppm\Process;

class ProcessExample extends Process
{
    public function exec()
    {
        $time = $this->getFormattedTime();
//        $this->output->writeln("[$time] heartbeat {$this->getPid()}");
        $this->output->writeln("[$time] heartbeat {$this->worker->pid}");
    }
}
