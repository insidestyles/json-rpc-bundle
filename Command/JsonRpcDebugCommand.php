<?php

namespace Insidestyles\JsonRpcBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
#[AsCommand('debug:json-rpc-api')]
final class JsonRpcDebugCommand extends Command
{
    protected static $defaultName = 'debug:json-rpc-api';
    protected function configure(): void
    {
        $this->setDescription('Api Debug');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln("Debug Action Needed");
    }
}
