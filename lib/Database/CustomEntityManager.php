<?php

namespace CLB\Database;

use CLB\Core\Config\Config;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;

class CustomEntityManager
{
    private static ?EntityManager $entityManager = null;
    private static ?Database $database = null;
    protected static ?CustomEntityManager $instance = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        try {
            self::$database = Database::getInstance();
        } catch (Exception $exception) {
            //dd(self::$database, $exception);
        }
        self::$instance = $this;


    }

    public static function getInstance(): ?CustomEntityManager
    {
        if(is_null(self::$instance)) {
            self::$instance = (new self());
        }
        return self::$instance;
    }
}