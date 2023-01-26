<?php

namespace CLB\Core\Repositories;
/**
 * @method static ConfigRepository repository()
 */
class ConfigRepository extends CollabRepository
{
    /**
     * @return mixed|object|null
     */
    public function getTimezone(): mixed
    {
        return $this->findOneBy(['app' => 'core', 'key' => 'timezone']);
    }
}