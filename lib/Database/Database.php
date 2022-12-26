<?php

namespace CLB\Database;

use CLB\Config\IConfig;
use CLB\Core\Config\Config;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Database implements IDatabase
{
    protected Config $config;
    protected Connection $connection;

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct(Config $config) {
        $this->config = $config;
        $this->connection = $this->connect();
        //dump($this->config, $this->connection);
    }

    public function getConfig($configName): IConfig
    {
        // TODO: Implement getConfig() method.
    }

    /**
     * @return Connection
     * @throws \Doctrine\DBAL\Exception
     */
    public function connect(): Connection
    {
        return DriverManager::getConnection($this->config->getConfig());
    }

    public function chooseDriver(): IDatabase
    {
        // TODO: Implement chooseDriver() method.
    }
}