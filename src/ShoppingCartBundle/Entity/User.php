<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="This email address is already taken by another user.")
 */
class User implements UserInterface
{
    CONST INITIAL_FUNDS = 2499;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     * @Assert\Range(min="0", max="100000")
     */
    private $funds;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"Registration"})
     * @Assert\Length(min="3", minMessage="Password must be longer than 3 symbols!")
     */
    private $plainPassword;

    /**
     * @var Role[]|ArrayCollection
     *
     * @Assert\Count(min="1")
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="users_roles")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->funds = self::INITIAL_FUNDS;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getRoles()
    {
        return $this->roles->toArray();
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        $this->email = $username;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getFunds()
    {
        return $this->funds;
    }

    public function setFunds($funds)
    {
        $this->funds = $funds;

        return $this;
    }

    public function addRole(Role $role)
    {
        $this->roles[] = $role;
    }
}