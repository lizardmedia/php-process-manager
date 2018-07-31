<?php


namespace Phppm;


use Symfony\Component\Console\Output\OutputInterface;

interface ProcessManagerInterface
{

    public function addWorker($process, OutputInterface $output, int $time);

    public function removeWorker();

}