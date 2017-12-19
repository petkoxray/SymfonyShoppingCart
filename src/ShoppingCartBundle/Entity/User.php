<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="This email address is already taken by another user.")
 */
class User implements UserInterface, AdvancedUserInterface
{
    CONST INITIAL_MONEY = 2499;

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
     * @var double $money
     *
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\Range(min="0", max="100000")
     */
    private $money;

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

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isBanned;

    /**
     * @var ArrayCollection|Product
     *
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Product", mappedBy="seller")
     */
    private $myProducts;

    /**
     * @var ArrayCollection|Product[]
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Product", inversedBy="userCart")
     * @ORM\JoinTable(name="users_carts")
     */
    private $cart;

    /**
     * @var Order[]|ArrayCollection $orders
     *
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Order", mappedBy="user")
     * @ORM\OrderBy({"createdAt":"desc"})
     */
    private $orders;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->orders = new ArrayCollection();

        $this->isBanned = false;
        $this->money = self::INITIAL_MONEY;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return Role[]
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * @param $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->email = $username;

        return $this;
    }

    /**
     * @param string $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return float
     */
    public function getMoney()
    {
        return $this->money;
    }

    public function setMoney($money): User
    {
        $this->money = $money;

        return $this;
    }

    /**
     * @param Role $role
     */
    public function addRole(Role $role): void
    {
        $this->roles[] = $role;
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return !$this->isBanned;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return !$this->isBanned();
    }

    /**
     * @return bool
     */
    public function isBanned()
    {
        return $this->isBanned;
    }

    /**
     * @param bool $isBanned
     */
    public function setIsBanned(bool $isBanned)
    {
        $this->isBanned = $isBanned;
    }

    /**
     * @return ArrayCollection|Review[]
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param ArrayCollection|Review[] $reviews
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * @return ArrayCollection|Product
     */
    public function getMyProducts()
    {
        return $this->myProducts;
    }

    /**
     * @param ArrayCollection|Product $myProducts
     */
    public function setMyProducts($myProducts)
    {
        $this->myProducts = $myProducts;
    }

    /**
     * @return ArrayCollection|Order[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param ArrayCollection|Order[] $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return ArrayCollection|Product
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param ArrayCollection|Product $cart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }
}