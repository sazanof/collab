<?php

namespace CLB\Database;

use CLB\Core\Exceptions\EntityAlreadyExistsException;
use CLB\Core\Exceptions\UserAlreadyExistsException;
use CLB\Serializer\JsonSerializer;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\PessimisticLockException;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use SebastianBergmann\Diff\Exception;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[ORM\MappedSuperclass]
class Entity
{
    private static EntityManager $em;

    private static function getStatic(): Entity
    {
        self::$em = Database::$em;
        return new static();
    }

    public static function create(): Entity
    {
        $class = self::getStatic();
        $args = func_get_args();
        $class->fromArray($args[0]);
        try {
            self::$em->persist($class);
            self::$em->flush();
        } catch (OptimisticLockException|ORMException $e) {
            dump($e);
        }
        return $class;
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