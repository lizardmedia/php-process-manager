<?php

namespace Phppm;

class Process implements ProcessInterface
{
    private $interval;

    private $process;
    /**
     * Process constructor.
     * @param int $interval in miliseconds
     */
    public function __construct(int $interval = 100)
    {
        $this->interval = $interval;

    }

    public function exec()
    {
        while (true) {

            usleep($this->interval*1000);

            echo 'test';
        }
    }

    public function open()
    {

    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function terminate()
    {
        // TODO: Implement terminate() method.
    }

    public function status()
    {
        // TODO: Implement status() method.
    }


}