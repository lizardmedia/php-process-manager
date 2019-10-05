<?php

namespace Phppm\Console;

use Phppm\ProcessManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class MasterProcess extends Command
{
    const DEFAULT_TIME = 50;

    protected function configure()
    {
        $this->setName('master process')
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
        $style = new SymfonyStyle($input, $output);

//        $style->title('Script demonizer.');
        //http://php.net/manual/en/function.uniqid.php
        //http://php.net/manual/en/function.ignore-user-abort.php - for lunch master process in back

        $time = $input->getOption('interval') ?: self::DEFAULT_TIME;

//        $style->comment('Execute with interval: ' . $time);


        $processManager = new ProcessManager();
        $processManager->addWorker('\\', $output, $time);
    }
}
