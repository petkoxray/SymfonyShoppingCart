<?php

namespace ShoppingCartBundle\Twig;


class ShoppingCartExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("starsRating", [$this, "starsRating"])
        );
    }

    public function starsRating($rating)
    {
        return str_repeat("<span class=\"fa fa-star checked\"></span>", $rating)
            . str_repeat("<span class=\"fa fa-star\" ></span>", 5 - $rating);
    }
}