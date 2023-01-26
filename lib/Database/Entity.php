<?php

namespace CLB\Database;

use CLB\Core\Exceptions\EntityAlreadyExistsException;
use CLB\Core\Repositories\CollabRepository;
use CLB\Serializer\JsonSerializer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ObjectRepository;

#[ORM\MappedSuperclass]
class Entity implements IEntity
{
    protected string $table = '';
    protected ?CustomEntityManager $em = null;
    protected static ?Entity $instance = null;
    protected ?Connection $connection = null;
    protected ?AbstractPlatform $platform = null;
    protected ?ORM\ClassMetadata $metadata = null;
    protected QueryBuilder $qb;
    protected string $className = '';
    protected ObjectRepository|CollabRepository $repository;

    protected array $fillable = [];

    /**
     * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct()
    {
        $this->em = $this->em();
        $this->qb = $this->em->createQueryBuilder();
        $this->className = get_class($this);
        $this->connection = $this->em->getConnection();
        $this->metadata = $this->em->getClassMetadata($this->className);
        $this->platform = $this->connection->getDatabasePlatform();
        $this->repository = $this->em->getRepository($this->className);
        self::$instance = $this;
        return $this;
    }

    /**
     * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
     */
    public function em(): ?CustomEntityManager
    {
        return Database::getInstance()->getEntityManager();
    }

    public static function get(): ?Entity
    {
        if(is_null(self::$instance)){
            return new static();
        }
        return self::$instance;
    }

    public static function repository(): ObjectRepository|CollabRepository|\Doctrine\ORM\EntityRepository
    {
        $class = self::get();
        return $class->repository;
    }

    protected static function initClass(array $args): Entity
    {
        $class = new static();
        $class->fromArray($args[0], $class->fillable);
        return $class;
    }



    /**
     * Assign entity properties using an array
     *
     * @param array $attributes assoc array of values to assign
     * @return null
     */
    public function fromArray(array $attributes, $allowedFields = array())
    {
        foreach ($attributes as $name => $value) {
            if (in_array($name, $allowedFields)) {
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
    }

    /**
     * Truncates the table
     * @return void
     */
    public function truncate(): void
    {
        $this->em->beginTransaction();
        try {
            $this->em->getConnection()
                ->executeQuery('SET FOREIGN_KEY_CHECKS=0');
            $q = $this->em->getConnection()
                ->getDatabasePlatform()
                ->getTruncateTableSql(
                    $this->metadata->getTableName()
                );
            $this->connection->executeQuery($q);
            $this->connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
            $this->connection->commit();
        }
        catch (\Exception) {
            $this->em->rollback();
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
        if ($repository instanceof $obj) {
            throw new EntityAlreadyExistsException($obj::class);
        }
    }

    /**
     * @inheritDoc
     */
    public static function find(int $id): Entity
    {
        $class = self::get();
        return $class->em->find($class->className, $id);
    }

    /**
     * @inheritDoc
     */
    public static function create(): Entity
    {
        $args = func_get_args();
        $class = self::initClass($args);
        if(!is_null($class->em)){
            try {
                $class->em->persist($class);
                $class->em->flush();
                return $class;
            } catch (EntityAlreadyExistsException $e) {
                $class->em->detach($class);
                $class->em->clear();
                //return false;
            } catch (ORMException|OptimisticLockException $e) {
                //return false;
            }
        }
        return $class;
    }

    /**
     * @inheritDoc
    */
    public function update(array $arguments): static
    {
        $this->className = get_class($this);
        $this->fromArray($arguments, $this->fillable);
        $this->em()->persist($this);
        $this->em()->flush();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function updateStaticByID(int $id, array $arguments): static
    {
        $class = self::get();
        return $class->em->find($class->className, $id)->update($arguments);
    }
}