<?php

namespace Phppm\Master;

use Phppm\Process as BaseProcess;

class Process extends BaseProcess
{
    
    //read process descriptions from files and check their work

    public function exec()
    {
        $time = $this->getFormattedTime();
        $this->output->writeln("[$time] heartbeat {$this->getPid()}");
    }
}
