<?php

namespace Phppm\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class Worker extends Command
{
    protected function configure()
    {
        $this->setName('worker')
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
        $script = $input->getArgument('script');

        $style->title('Script worker.');

        $time = $input->getOption('interval') ?: Run::DEFAULT_TIME;

        $style->comment('Execute with interval: ' . $time);

        if (file_exists($script)) {
            require_once $script;
            $namespace = $this->getNamespace($script);

            $worker = new \Phppm\ProcessWorker('\\' . $namespace, $output, $time);
            $worker->runProcess();
        }
    }

    /**
     * @param string $file
     * @return string
     */
    protected function getNamespace(string $file) : string
    {
        $source = file_get_contents($file);

        if (preg_match('#^namespace\s+(.+?);.*class\s+(\w+).+;$#sm', $source, $matches)) {
            return $matches[1].'\\'.$matches[2];
        }

        return '';
    }
}
