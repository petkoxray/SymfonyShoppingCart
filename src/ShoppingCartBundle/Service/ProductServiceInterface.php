<?php
/**
 * Created by PhpStorm.
 * User: petkoxray
 * Date: 18.12.17
 * Time: 17:08
 */

namespace ShoppingCartBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;

interface ProductServiceInterface
{
    /**
     * @param Product[]
     * @return bool
     */
    public function isProductsAvailable($products): bool;
}