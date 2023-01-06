<?php

namespace CLB\Core\Models;
use CLB\Database\Entity;
use CLB\Database\Trait\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Index(columns: ['id'], name: 'user_id')]
#[ORM\Index(columns: ['email'], name: 'email')]
#[ORM\Table(name: '`users`')]
class User extends Entity
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $username;

    #[ORM\Column(type: Types::STRING)]
    private string $password;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $email;

    #[ORM\Column(type: Types::STRING)]
    private string $firstname;

    #[ORM\Column(type: Types::STRING)]
    private string $lastname;

    #[ORM\JoinTable(name: 'users_groups')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\OrderBy(["name" => "ASC"])]
    private Collection|ArrayCollection $groups;

    #[ORM\JoinTable(name: 'users_permissions')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'permission_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Permissions::class)]
    private Collection $permissions;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @param array $groups
     */
    public function setGroups(array $groups): void
    {
        $this->groups = new ArrayCollection($groups);
    }

    public function clearGroups(): void
    {
        $this->groups = new ArrayCollection([]);
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getGroups(): ArrayCollection|Collection
    {
        return $this->groups;
    }

    /**
     * @return Collection
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    /**
     * @param Collection $permissions
     */
    public function setPermissions(Collection $permissions): void
    {
        $this->permissions = new ArrayCollection($permissions);
    }
}