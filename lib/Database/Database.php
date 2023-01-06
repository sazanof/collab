<?php

namespace CLB\Database;

use CLB\Core\Config\Config;
use CLB\Core\Events\TablePrefix;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMSetup;

class Database implements IDatabase
{
    protected Config $config;
    protected Configuration $configuration;
    public Connection $connection;
    public static ?EntityManager $em = null;

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct(Config $config) {
        $this->config = $config;
        $this->connection = $this->connect();
        return $this;
        //dump($this->config, $this->connection);
    }

    public function getConfig($configName): array
    {
        return $this->config->getConfig();
    }

    /**
     * @return Connection
     * @throws \Doctrine\DBAL\Exception
     */
    public function connect(): Connection
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: array(realpath('../core/Models')),
            isDevMode: true,
        );
        $this->configuration = $config;

        $evm = new EventManager();
        $tablePrefix = new TablePrefix($this->config->getConfigValue('prefix'));
        $evm->addEventListener(Events::loadClassMetadata, $tablePrefix);

        return DriverManager::getConnection($this->config->getConfig(), $config, $evm);
    }

    /**
     * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
     */
    public function getEntityManager() : EntityManager{
        self::$em = self::$em instanceof EntityManager ? self::$em : new EntityManager($this->connection, $this->configuration);
        return self::$em;
    }

    public function chooseDriver(): IDatabase
    {
        // TODO: Implement chooseDriver() method.
    }
}