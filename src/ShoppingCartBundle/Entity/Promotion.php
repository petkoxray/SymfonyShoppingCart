<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\PromotionRepository")
 * @ORM\Table(name="promotion")
 */
class Promotion
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="100")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="discount", type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(min="1", max="99")
     */
    private $discount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     * @Assert\NotBlank()
     * @Assert\GreaterThan("-1 month")
     * @Assert\Date()
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     * @Assert\Expression(
     *     "this.getEndDate() > this.getStartDate()",
     *     message="End date should be greater than start date!"
     * )
     * @Assert\NotBlank()
     * @Assert\GreaterThan("now")
     * @Assert\Date()
     */
    private $endDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Product", mappedBy="promotions", fetch="EXTRA_LAZY")
     */
    private $products;

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
    public function getName(): ?string
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
     * @return int
     */
    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    /**
     * @param int $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ArrayCollection $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return  bool
     */
    public function isActive()
    {
        if ($this->getStartDate() < new \DateTime('now') &&
            $this->getEndDate() > new \DateTime('now')) {
            return true;
        }

        return false;
    }

    public function __toString()
    {
        return $this->getName() . " / {$this->getDiscount()} % discount on products";
    }
}