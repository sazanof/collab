<?php

namespace CLB\Core\Console\Commands;

use CLB\Core\Config\Config;
use CLB\Database\Database;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Doctrine\Migrations\Tools\Console\Command as DoctrineCommand;
use Symfony\Component\Console\Application;

#[AsCommand(name: 'migration')]
class MigrationCommand extends Command
{
    protected string $name = '';
    protected string $path;
    protected string $className = '';
    protected Database $database;

    public function __construct(string $name = '')
    {
        $this->name = $name;
        $config = new Config('database');
        $connection = DriverManager::getConnection($config->getConfig());
        $configuration = new Configuration($connection);

        $configuration->addMigrationsDirectory('Clb\Migrations', 'database/migrations');
        $configuration->setAllOrNothing(true);
        $configuration->setCheckDatabasePlatform(false);

        $storageConfiguration = new TableMetadataStorageConfiguration();
        $storageConfiguration->setTableName('migrations');

        $configuration->setMetadataStorageConfiguration($storageConfiguration);

        $dependencyFactory = DependencyFactory::fromConnection(
            new ExistingConfiguration($configuration),
            new ExistingConnection($connection)
        );

        $cli = new Application('Collab Doctrine Migrations');
        $cli->setCatchExceptions(true);

        $cli->addCommands(array(
            new DoctrineCommand\CurrentCommand($dependencyFactory),
            new DoctrineCommand\DiffCommand($dependencyFactory),
            new DoctrineCommand\DumpSchemaCommand($dependencyFactory),
            new DoctrineCommand\ExecuteCommand($dependencyFactory),
            new DoctrineCommand\GenerateCommand($dependencyFactory),
            new DoctrineCommand\LatestCommand($dependencyFactory),
            new DoctrineCommand\ListCommand($dependencyFactory),
            new DoctrineCommand\MigrateCommand($dependencyFactory),
            new DoctrineCommand\RollupCommand($dependencyFactory),
            new DoctrineCommand\StatusCommand($dependencyFactory),
            new DoctrineCommand\SyncMetadataCommand($dependencyFactory),
            new DoctrineCommand\UpToDateCommand($dependencyFactory),
            new DoctrineCommand\VersionCommand($dependencyFactory),
        ));

        $cli->run();
        parent::__construct();
    }

}