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
    protected static ApplicationUtilities $instance;
    protected Database $database;

    public function __construct(){
        $CLB_Version = '';
        $CLB_VersionArray = [];
        require realpath('../inc/version.php');
        $this->version = $CLB_Version;
        $this->versionArray = $CLB_VersionArray;
        $this->database = Database::getInstance();
        self::$instance = $this;
    }

    public static function getInstance(): ApplicationUtilities
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setEntityManager(EntityManager $em): ?EntityManager
    {
        $this->entityManager = $em;
        return $this->entityManager;
    }

    public function getEntityManager(): ?EntityManager
    {
        return $this->entityManager;
    }

    public function getDatabase(): Database
    {
        return $this->database;
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
     * Gets version of any App
     * @return mixed|object
     * @throws WrongConfigurationException
     */
    public function getDatabaseAppVersion(string $key = null): mixed
    {
        $dbVersion = $this->entityManager->getRepository(Config::class)->findOneBy([
            'app' => is_null($key) ? Application::$configKey : $key,
            'key' => 'version'
        ]);
        if(is_null($dbVersion)){
            throw new WrongConfigurationException();
        }
        return $dbVersion;
    }

    /**
     * @throws \Throwable
     * @throws EntityManagerNotDefinedException
     */
    public function checkVersion(){
        if(is_null($this->entityManager)){
            throw new EntityManagerNotDefinedException();
        }
        Database::getInstance()->getEntityManager()->wrapInTransaction(function (){
            $v = $this->getDatabaseAppVersion();
            //TODO compare version in file and in database
            // if not equals = UPGRADE PROCESS
        });
    }
}