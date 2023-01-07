<?php

namespace CLB\Database\Install;

use CLB\Core\Config\Config;
use \CLB\Core\Models\Config as ORMConfig;
use CLB\Core\Config\DatabaseConfig;
use CLB\Database\Database;
use Doctrine\ORM\EntityManager;

class UpdateConfigAfterInstall extends Database
{
    protected Config $config;
    protected EntityManager $entityManager;

    /**
     * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct()
    {
        $this->config = new DatabaseConfig();
        parent::__construct($this->config);
        $this->entityManager = $this->getEntityManager();
    }

    /**
     * @throws \Throwable
     */
    private function addBaseConfigValues(){
        $app = 'app';
        $this->entityManager->getRepository(ORMConfig::class);
        $this->entityManager->wrapInTransaction(function() use ($app){
            ORMConfig::create([
                'app' => $app,
                'key' => 'version',
                'value' => ''
            ]);
        });
    }
}