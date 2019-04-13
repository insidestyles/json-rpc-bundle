<?php

namespace Insidestyles\JsonRpcBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
final class JsonRpcDebugCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('debug:json-rpc-api')
            ->setDescription('Api Debug');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Debug Action Needed");
    }
}
