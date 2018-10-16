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
    protected $debug = false;

    /**
     * @var bool
     */
    protected $alarm = false;

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

        $this->handlers = array_replace($this->signalHandler(), $this->process->signalHandler());
        $this->isRunning = true;

        declare(ticks=1);

        $pid = pcntl_fork();

        if ($pid === -1) {
            die("could not fork");
        } elseif ($pid) {
            echo 'MASTER: ' . getmypid() . "; $pid" . PHP_EOL;
            exit(); // we are the parent
        } else {
            $this->pid = getmypid();
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
     *
     * @see http://www.gnu.org/software/libc/manual/html_node/Standard-Signals.html#Standard-Signals
     */
    protected function signalHandler() : array
    {
        return [
            SIGTERM => function () {
                echo 'I was terminated :(' . PHP_EOL;
                $this->killMe(SIGTERM);
            },
            SIGHUP => function () {
                pcntl_sigprocmask(SIG_BLOCK, [SIGCONT]);

                echo 'Waiting for signals. Process suspended.' . PHP_EOL;
                $info = [];
                pcntl_sigwaitinfo([SIGCONT], $info);
            },
            SIGABRT => function () {
                echo 'I was aborted.' . PHP_EOL;
                $this->killMe(SIGABRT);
            },
            SIGINT => function () {
                echo 'I was interrupted.' . PHP_EOL;
                $this->killMe(SIGINT);
            },
            SIGXCPU => function () {
                $limits = posix_getrlimit();
                echo 'Overflow CPU limits: soft-'
                    . $limits['soft cpu']
                    . '; hard-'
                    . $limits['hard cpu']
                    . PHP_EOL;
                $this->killMe(SIGXCPU);
            },
            SIGALRM => function () {
                $this->alarm = true;
            },
            SIGQUIT => function () {
                echo 'I was quited.' . PHP_EOL;
                $this->killMe();
            },
            SIGXFSZ => function () {
                $limits = posix_getrlimit();
                echo 'File size limit exceeded: soft-'
                    . $limits['soft filesize']
                    . '; hard-'
                    . $limits['hard filesize']
                    . PHP_EOL;
                $this->killMe(SIGXFSZ);
            },
            SIGTTOU => function () {
                //write to log
            },
            SIGUSR1 => function () {
                echo 'User defined signal: ' . SIGUSR1;
            },
            SIGUSR2 => function () {
                echo 'User defined signal: ' . SIGUSR2;
            },
            SIGINFO => function () {
                echo 'Process information.';
            },
            SIGTRAP => function () {
                $this->debug = $this->debug ? false : true;
                echo 'Switch on/off debug mode.';
            },
        ];
    }

    /**
     * @param int $code
     */
    protected function killMe(int $code = 0)
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

    /**
     * Return and set to false SIGALRM triggering
     *
     * @return bool
     */
    public function hasAlarm() : bool
    {
        $alarm = $this->alarm;
        $this->alarm = false;

        return $alarm;
    }

    /**
     * @return bool
     */
    public function isDebug() : bool
    {
        return $this->debug;
    }
}
