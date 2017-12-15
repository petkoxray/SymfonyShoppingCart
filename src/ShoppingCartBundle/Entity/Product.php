<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\ProductRepository")
 * @ORM\Table(name="product")
 * @Vich\Uploadable()
 */
class Product
{
    /**
     * @var int $id
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", unique=false, length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(min="0", max="5000")
     */
    private $quantity;

    /**
     * @var string $imageName
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $imageName;
    /**
     * @var File $imageFile
     *
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     * @Assert\NotNull(groups={"NewProduct"}, message="You should upload a product image.")
     * @Assert\NotBlank(message="Please upload image!")
     * @Assert\Image()
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity="ShoppingCartBundle\Entity\Category", inversedBy="products",  fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @var double $price
     *
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank()
     * @Assert\Range(min="0.01")
     */
    private $price;

    /**
     * @var double $promoPrice
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $promoPrice;

    /**
     * @var string $slug
     *
     * @ORM\Column(nullable=false, type="string", unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @var Review[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Review", mappedBy="product")
     */
    private $reviews;

    /**
     * @var Promotion[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Promotion")
     * @ORM\JoinTable(name="product_promotions")
     */
    private $promotions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ShoppingCartBundle\Entity\User", inversedBy="myProducts")
     */
    private $seller;

    /**
     * @var User
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\User", mappedBy="cart")
     * @ORM\JoinTable(name="users_carts")
     */
    private $userCart;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
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
     * @return float
     */
    public function getPromoPrice(): float
    {
        return $this->promoPrice;
    }

    /**
     * @param float $promoPrice
     */
    public function setPromoPrice(float $promoPrice)
    {
        $this->promoPrice = $promoPrice;
    }

    /**
     * @return ArrayCollection|Promotion[]
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

    /**
     * @param ArrayCollection|Promotion[] $promotions
     */
    public function setPromotions($promotions)
    {
        $this->promotions = $promotions;
    }

    /**
     * @return int
     */
    public function getAverageRating(): int
    {
        if (count($this->getReviews()) > 0) {
            $sum = array_reduce($this->getReviews()->toArray(), function ($sum, Review $review) {
                $sum += $review->getRating();

                return $sum;
            });

            return floor($sum / count($this->getReviews()));
        }

        return 0;
    }
}