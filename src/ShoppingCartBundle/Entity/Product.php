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
     * @ORM\Column(type="string", unique=false)
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
     */
    private $imageName;
    /**
     * @var File $imageFile
     *
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     * @Assert\NotNull(groups={"NewProduct"}, message="You should upload a product image.")
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getImageName(): string
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