<?php

namespace CLB\Core\Models;

use CLB\Database\Entity;
use CLB\Database\IdGenerator;
use CLB\Database\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[ORM\Entity]
#[ORM\Table(name: '`config`')]
#[ORM\UniqueConstraint(name: 'app_key', columns: ['app', 'key'])]
#[ORM\Index(columns: ['app','key'], name: 'app_key')]
class Config extends Entity
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: IdGenerator::class)]
    private int|null $id = null;

    #[ORM\Column(name: '`app`', type: Types::STRING, length: 64)]
    private string $app;

    #[ORM\Column(name: '`value`' ,type: Types::STRING, length: 64)]
    private string $value;

    #[ORM\Column(name: '`key`', type: Types::STRING, length: 255)]
    private string $key;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param string $app
     */
    public function setApp(string $app): void
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function getApp(): string
    {
        return $this->app;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @throws \CLB\Core\Exceptions\EntityAlreadyExistsException
     */
    #[ORM\PrePersist]
    public function checkConfigOnDuplicate(LifecycleEventArgs $args){
        $this->checkExistingRecords(['app' => $this->app, 'key' => $this->key], $args);
    }
}