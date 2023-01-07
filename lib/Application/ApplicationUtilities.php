<?php

namespace CLB\Application;

use CLB\Core\Application;
use CLB\Core\Exceptions\EntityManagerNotDefinedException;
use CLB\Core\Exceptions\WrongConfigurationException;
use CLB\Core\Models\Config;
use CLB\Database\Database;
use Doctrine\ORM\EntityManager;

class ApplicationUtilities
{
    private string $version;
    private array $versionArray;
    private ?EntityManager $entityManager = null;

    public function __construct(){
        $CLB_Version = '';
        $CLB_VersionArray = [];
        require realpath('../inc/version.php');
        $this->version = $CLB_Version;
        $this->versionArray = $CLB_VersionArray;
    }

    public function setEntityManager(EntityManager $em) {
        $this->entityManager = $em;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getVersionArray(): array
    {
        return $this->versionArray;
    }

    /**
     * @throws \Throwable
     * @throws EntityManagerNotDefinedException
     */
    public function checkVersion(){
        if(is_null($this->entityManager)){
            throw new EntityManagerNotDefinedException();
        }
        Database::$em->wrapInTransaction(function (){
            $dbVersion = $this->entityManager->getRepository(Config::class)->findOneBy([
                'app' => Application::$configKey,
                'key' => 'version'
            ]);
            if(is_null($dbVersion)){
                throw new WrongConfigurationException();
            }
            //TODO compare version in file and in database
            // if not equals = UPGRADE PROCESS
        });
    }
}