<?php

namespace Phppm;

use Symfony\Component\Console\Output\OutputInterface;

class ProcessManager implements ProcessManagerInterface
{
    protected $workers;

    public function addWorker(string $process, OutputInterface $output, int $time)
    {
        $descriptorspec = [
            0 => ["pipe", "r"],  // stdin is a pipe that the child will read from
            1 => ["pipe", "w"],  // stdout is a pipe that the child will write to
            2 => ["file", "/tmp/error-output.txt", "a"] // stderr is a file to write to
        ];

        $process = proc_open('./bin/phppm worker -i 1000 Phppm\\\\Process', $descriptorspec, $this->pipes);

//        $this->workers[] = $worker;
    }

    public function removeWorker()
    {
        // TODO: Implement removeWorker() method.
    }
}
