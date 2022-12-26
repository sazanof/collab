<?php

namespace CLB\Core\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'migration:create')]
class MigrationCreateCommand extends Command
{
    protected string $name = '';
    protected string $path;
    protected string $className = '';

    public function __construct(string $name = '')
    {
        $this->name = $name;

        parent::__construct();
    }

    protected function setMigrationClassName($name){
        $now = date('Ymd_His');
        $this->className = "{$now}_Migration_{$name}.php";
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setMigrationClassName($input->getArgument('name'));
        dump($this->className);
        return Command::SUCCESS;
    }
}