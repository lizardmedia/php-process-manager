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

    public function runProcess() : void
    {
        if ($this->isRunning) {
            return;
        }

        $this->handlers = array_merge($this->signalHandler(), $this->process->signalHandler());

        $this->pid = getmypid();
        $this->isRunning = true;

        $handler = function ($signal) {
            switch ($signal) {
                case SIGTERM:
                    // handle shutdown tasks
                    echo 'I was terminated :(' . PHP_EOL;
                    exit;
                case SIGHUP:
                    pcntl_sigprocmask(SIG_BLOCK, array(SIGCONT));

//            echo "Sending SIGHUP to self\n";
//            posix_kill(posix_getpid(), SIGHUP);

                    echo 'Waiting for signals' . PHP_EOL;
                    $info = array();
                    pcntl_sigwaitinfo(array(SIGCONT), $info);
                    // handle restart tasks
                    break;

                case SIGINT:
                    echo 'I was interrupted.' . PHP_EOL;
                    exit;

                case SIGKILL:
                    echo 'I was killed X|' . PHP_EOL;
                    exit;

//                    SIGXCPU
//                    SIGXFSZ
                default:
                    // handle all other signals
            }
        };

        declare(ticks=1);


        $pid = pcntl_fork();
        if ($pid == -1) {
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

        // setup signal handlers
        pcntl_signal(SIGTERM, $handler);
        pcntl_signal(SIGHUP, $handler);
        pcntl_signal(SIGINT, $handler);
        pcntl_signal(SIGKILL, $handler);
        dump(getmypid());

        while (true) {
            $this->process->exec();
            usleep($this->interval * 1000);

            if ($this->stop) {
                break;
            }
        }
    }

    protected function signalHandler()
    {
        return [
            SIGTERM => function () {
                echo 'I was terminated :(' . PHP_EOL;
                exit;
            }
        ];
    }

    protected function registerHandlers()
    {
        foreach ($this->handlers as $signal => $handler) {
            pcntl_signal($signal, $handler);
        }
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
    public function stop()
    {
        $this->stop = true;

        return $this;
    }
}
