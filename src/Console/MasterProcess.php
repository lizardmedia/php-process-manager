<?php

namespace Phppm\Console;

use Phppm\ProcessManager;
use Phppm\Master\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class MasterProcess extends Command
{
    const DEFAULT_TIME = 50;

    const MAIN_CONFIG_DIR = __DIR__ . '/../../process/';
    const MAIN_CONFIG = self::MAIN_CONFIG_DIR . 'main.json';

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var int
     */
    protected $pid;

    /**
     * @var SymfonyStyle
     */
    protected $style;

    protected function configure()
    {
        $this->setName('master-process')
            ->setDescription('Monitoring process for all sub processes.')
            ->setHelp('');

        $this->addOption('interval', 'i', InputArgument::OPTIONAL, 'Master process monitoring interval time.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        ignore_user_abort(true);
        $style = new SymfonyStyle($input, $output);
        $this->uuid = uniqid(rand() . '.', true);
        $this->style = new SymfonyStyle($input, $output);
        $this->pid = getmypid();

//        $style->title('Script demonizer.');
        //http://php.net/manual/en/function.uniqid.php
        //http://php.net/manual/en/function.ignore-user-abort.php - for lunch master process in back

        $time = $input->getOption('interval') ?: self::DEFAULT_TIME;

        $style->comment('Starting Master Process observer with interval: ' . $time);

        $this->clearProcessesConfig()
            ->setMasterProcessConfig();

        $worker = new \Phppm\ProcessWorker(Process::class, $output, $time);
        $worker->runProcess();
    }

    protected function clearProcessesConfig() : self
    {
        (new Filesystem())->remove(
            glob(self::MAIN_CONFIG_DIR . 'processes/*.json')
        );

        return $this;
    }

    protected function setMasterProcessConfig() : self
    {
        $data = [
            'pid' => $this->pid,
            'uuid' => $this->uuid,
        ];

        if (!file_exists(self::MAIN_CONFIG)) {
            touch(self::MAIN_CONFIG);
        }

        file_put_contents(self::MAIN_CONFIG, json_encode($data, JSON_PRETTY_PRINT));

        return $this;
    }
}
