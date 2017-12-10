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
     * @var int $id
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $email
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private $email;

    /**
     * @var string $fullName
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @var double $funds
     *
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\Range(min="0", max="100000")
     */
    private $funds;

    /**
     * @var string
     *
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

    /**
     * @var Review[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Review", mappedBy="author")
     */
    private $reviews;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->funds = self::INITIAL_FUNDS;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles->toArray();
    }

    public function setRoles($roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setUsername($username): User
    {
        $this->email = $username;

        return $this;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName($fullName): User
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getFunds(): ?float
    {
        return $this->funds;
    }

    public function setFunds($funds): User
    {
        $this->funds = $funds;

        return $this;
    }

    public function addRole(Role $role): void
    {
        $this->roles[] = $role;
    }
}