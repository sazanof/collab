<?php

namespace CLB\Core\Models;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use CLB\Database\Trait\Timestamps;

#[ORM\Entity]
#[ORM\Index(columns: ['id'], name: 'group_id')]
#[ORM\Table(name: '`groups`')]
class Group
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $name;
}