<?php

namespace CLB\Database;

use CLB\Config\IConfig;
use Doctrine\DBAL\Connection;

interface IDatabase
{
    public function getConfig($configName) : IConfig;

    public function connect() : Connection;

    public function chooseDriver() : self;
}