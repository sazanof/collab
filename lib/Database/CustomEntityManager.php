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
            self::$database = new Database(new Config('database'));
        } catch (Exception $exception) {
            //dd(self::$database, $exception);
        }


    }

    public static function getInstance(): void
    {
        if(is_null(self::$instance)) {
            self::$instance = (new self());
        }
    }

    public static function em(): ?EntityManager
    {
        self::getInstance();

        if(is_null(self::$entityManager) && self::$database instanceof Database){
            self::$entityManager = self::$database->getEntityManager();
        }
        return self::$entityManager;
    }
}