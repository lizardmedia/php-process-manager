<?php


namespace Phppm;


use Symfony\Component\Console\Output\OutputInterface;

class ProcessManager implements ProcessManagerInterface
{
    private $workers;


    public function addWorker($process, OutputInterface $output, int $time)
    {

        $worker = new \Phppm\ProcessWorker($process, $output, $time);
        $worker->runProcess();
        $this->workers[] = $worker;
    }

    public function removeWorker()
    {
        // TODO: Implement removeWorker() method.
    }

}