<?php

namespace CLB\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

/**
 * Id generator that obtains IDs from special "identity" columns. These are columns
 * that automatically get a database-generated, auto-incremented identifier on INSERT.
 * This generator obtains the last insert id after such an insert.
 */
class IdGenerator extends AbstractIdGenerator
{
    /**
     * The name of the sequence to pass to lastInsertId(), if any.
     *
     * @var string
     */
    private ?string $sequenceName;

    /**
     * Constructor.
     *
     * @param string|null $sequenceName The name of the sequence to pass to lastInsertId()
     *                                  to obtain the last generated identifier within the current
     *                                  database session/connection, if any.
     */
    public function __construct($sequenceName = null)
    {
        $this->sequenceName = $sequenceName;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(EntityManager $em, $entity)
    {
        return (int)$em->getConnection()->lastInsertId($this->sequenceName);
    }

    /**
     * {@inheritdoc}
     */
    public function isPostInsertGenerator()
    {
        return true;
    }
}