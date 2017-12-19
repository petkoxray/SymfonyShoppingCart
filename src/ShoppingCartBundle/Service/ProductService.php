<?php

namespace ShoppingCartBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use ShoppingCartBundle\Entity\Product;

class ProductService implements ProductServiceInterface
{
    /**
     * @param ArrayCollection|Product $products
     * @return bool
     */
    public function isProductsInStock($products): bool
    {
        foreach ($products as $product) {
            if (!$product->isInStock()) {
                return false;
            }
        }

        return true;
    }

}