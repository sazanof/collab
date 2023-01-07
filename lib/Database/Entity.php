<?php

namespace CLB\Database;

use CLB\Core\Exceptions\EntityAlreadyExistsException;
use CLB\Serializer\JsonSerializer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
class Entity
{
    private static EntityManager $em;
    private static ?Entity $instance = null;

    /**
     * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
     */
    public static function getInstance(): Entity
    {
        if(is_null(self::$instance)) {
            self::$instance = (new static());
        }
        return self::$instance;
    }

    public static function create(): Entity
    {
        $class = self::getInstance();
        $em = Database::getInstance()->getEntityManager();
        $args = func_get_args();
        $class->fromArray($args[0]);
        try {
            $em->persist($class);
            $em->flush();
        } catch (OptimisticLockException|ORMException $e) {
            dump($e);
        }
        return $class;
    }

    public static function insertIgnore(){
        $class = self::getInstance();
    }

    /**
     * Assign entity properties using an array
     *
     * @param array $attributes assoc array of values to assign
     * @return null
     */
    public function fromArray(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            if (property_exists($this, $name)) {
                $methodName = $this->_getSetterName($name);
                if ($methodName) {
                    $this->{$methodName}($value);
                } else {
                    $this->$name = $value;
                }
            }
        }
    }

    /**
     * Get property setter method name (if exists)
     *
     * @param string $propertyName entity property name
     * @return false|string
     */
    protected function _getSetterName(string $propertyName): bool|string
    {
        $prefixes = array('add', 'set');

        foreach ($prefixes as $prefix) {
            $methodName = sprintf('%s%s', $prefix, ucfirst(strtolower($propertyName)));

            if (method_exists($this, $methodName)) {
                return $methodName;
            }
        }
        return false;
    }

    public function toJSON(): string
    {
        return JsonSerializer::serializeStatic($this);
    }

    /**
     * Check if Entity already exists
     *
     * @param array $fields
     * @param LifecycleEventArgs $args
     * @return void
     * @throws EntityAlreadyExistsException
     */
    protected function checkExistingRecords(array $fields, LifecycleEventArgs $args): void
    {
        $obj = $args->getObject();
        $repository = $args->getObjectManager()->getRepository($obj::class)->findOneBy($fields);
        if($repository instanceof $obj) {
            throw new EntityAlreadyExistsException($obj::class);
        }
    }
}