<?php

namespace CLB\Database;

use CLB\Config\IConfig;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

interface IDatabase
{
    public function getConfig() : array;

    public function connect() : Connection|null;

    public function getEntityManager() : EntityManager|null;

    public function chooseDriver() : self;
}