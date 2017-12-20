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
    public function isProductsAvailable($products): bool
    {
        /**
         * @var Product $product
         */
        foreach ($products as $product) {
            if (!$product->isInStock() || !$product->isListed()) {
                return false;
            }
        }

        return true;
    }

}