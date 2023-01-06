<?php

namespace CLB\Database;

use CLB\Config\IConfig;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

interface IDatabase
{
    public function getConfig($configName) : array;

    public function connect() : Connection;

    public function getEntityManager() : EntityManager;

    public function chooseDriver() : self;
}