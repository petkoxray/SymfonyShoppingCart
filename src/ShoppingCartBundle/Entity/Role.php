<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="role")
 */
class Role extends \Symfony\Component\Security\Core\Role\Role
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\User", mappedBy="roles")
     */
    private $users;

    public function __construct($role)
    {
        parent::__construct($role);
        $this->users = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function getRole(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return ucfirst(strtolower(explode("_", $this->name)[1]));
    }
}
