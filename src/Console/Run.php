<?php

namespace Phppm\Console;

use Phppm\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class Run extends Command
{
    const DEFAULT_TIME = 100;

    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Execute worker with given process script.')
            ->setHelp('');

        $this->addArgument(
            'script',
            InputArgument::REQUIRED,
            'Script that will be demonized'
        );

        $this->addOption('interval', 'i', InputArgument::OPTIONAL, 'Worker interval time.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        $style->title('Script demonizer.');

        $time = $input->getOption('interval') ?: self::DEFAULT_TIME;

        $style->comment('Execute with interval: ' . $time);

        $process = new Process($time);
        $process->exec();
    }
}
