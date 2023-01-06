<?php

namespace CLB\Core\Models;

use CLB\Database\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: '`config`')]
class Config
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT)]
    #[ORM\GeneratedValue]
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
}