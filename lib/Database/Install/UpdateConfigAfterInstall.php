<?php

namespace CLB\Database\Install;

use CLB\Application\ApplicationUtilities;
use CLB\Core\Config\Config;
use CLB\Core\Exceptions\EntityAlreadyExistsException;
use CLB\Core\Models\Config as ORMConfig;
use Doctrine\ORM\EntityManager;

class UpdateConfigAfterInstall
{
    protected Config $config;
    protected ?EntityManager $entityManager = null;
    protected ApplicationUtilities $utilities;

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct()
    {
        $this->utilities = ApplicationUtilities::getInstance();
        $this->entityManager = $this->utilities->getEntityManager();
    }

    /**
     * @throws \Throwable
     */
    public function addBaseConfigValues(): void
    {
        $app = 'app';
        $this->entityManager->getRepository(ORMConfig::class);
        try {
            ORMConfig::create([
                'app' => $app,
                'key' => 'version',
                'value' => $this->utilities->getVersion()
            ]);
        } catch (EntityAlreadyExistsException $e) {

        }


    }
}