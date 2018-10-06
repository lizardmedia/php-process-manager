<?php

namespace Phppm;

use Symfony\Component\Console\Output\OutputInterface;

class ProcessWorker
{
    /**
     * @var int
     */
    protected $interval;

    /**
     * @var ProcessInterface
     */
    public $process;

    /**
     * @var int
     */
    protected $pid;

    /**
     * @var bool
     */
    protected $isRunning = false;

    /**
     * @var bool
     */
    protected $stop = false;

    /**
     * @var array
     */
    protected $handlers;

    /**
     * ProcessWorker constructor.
     *
     * @param string $process
     * @param OutputInterface $output
     * @param int $interval
     */
    public function __construct(string $process, OutputInterface $output, int $interval = 100)
    {
        $this->process = new $process($output, $this);
        $this->interval = $interval;
    }

    public function runProcess()
    {
        if ($this->isRunning) {
            return;
        }

        $this->handlers = array_merge($this->signalHandler(), $this->process->signalHandler());

        $this->pid = getmypid();
        $this->isRunning = true;

        declare(ticks=1);

        $pid = pcntl_fork();

        if ($pid === -1) {
            die("could not fork");
        } elseif ($pid) {
            echo 'MASTER: ' . getmypid() . "; $pid" . PHP_EOL;
            exit(); // we are the parent
        } else {
            echo 'child: ' . getmypid() . PHP_EOL;
            // we are the child
        }

        // detatch from the controlling terminal
        if (posix_setsid() == -1) {
            die("could not detach from terminal");
        }

        $this->registerHandlers();
//        dump(getmypid());

        while (true) {
            $this->process->exec();
            usleep($this->interval * 1000);

            if ($this->stop) {
                break;
            }
        }
    }

    /**
     * @return array
     */
    protected function signalHandler() : array
    {
        return [
            SIGTERM => function () {
                echo 'I was terminated :(' . PHP_EOL;
                $this->killMe();
            },
            SIGHUP => function () {
                pcntl_sigprocmask(SIG_BLOCK, [SIGCONT]);

                echo 'Waiting for signals' . PHP_EOL;
                $info = [];
                pcntl_sigwaitinfo([SIGCONT], $info);
            },
            SIGINT => function () {
                echo 'I was interrupted.' . PHP_EOL;
                $this->killMe();
            },
            SIGKILL => function () {
                echo 'I was killed X(' . PHP_EOL;
                $this->killMe(1);
            },
            SIGXCPU => function () {
                $limits = posix_getrlimit();
                echo 'Overflow CPU limits: soft-' . $limits['soft cpu'] . '; hard-' . $limits['hard cpu'] . PHP_EOL;
                $this->killMe(1);
            }
        ];
    }

    /**
     * @param int $code
     */
    protected function killMe($code = 0)
    {
        exit($code);
    }

    /**
     * @return $this
     */
    protected function registerHandlers() : self
    {
        foreach ($this->handlers as $signal => $handler) {
            pcntl_signal($signal, $handler);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getPid() : int
    {
        return $this->pid;
    }

    /**
     * @return $this
     */
    public function stop() : self
    {
        $this->stop = true;

        return $this;
    }
}
