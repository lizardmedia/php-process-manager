<?php

namespace Phppm\Console;

use Phppm\ProcessManager;
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
            ->setDescription('Run given script as demon process.')
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

        $style->title('Script demonizer.');

        $time = $input->getOption('interval') ?: self::DEFAULT_TIME;

        $style->comment('Execute with interval: ' . $time);

        if (file_exists($script)) {
            require_once $script;
            $namespace = $this->getNamespace($script);

            $processManager = new ProcessManager();
            $processManager->addWorker('\\' . $namespace, $output, $time);
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
