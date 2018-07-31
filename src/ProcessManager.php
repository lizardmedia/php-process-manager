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

        $process = proc_open('php', $descriptorspec, $this->pipes);

        dump(proc_get_status($process));

        stream_set_blocking($this->pipes[1], 0);
        stream_set_blocking($this->pipes[2], 0);
        dump($this->pipes);
        
//        fclose($this->pipes[0]);
//        fclose($this->pipes[1]);
        
        proc_close($process);
        
        
//        $this->workers[] = $worker;
    }

    public function removeWorker()
    {
        // TODO: Implement removeWorker() method.
    }
}
