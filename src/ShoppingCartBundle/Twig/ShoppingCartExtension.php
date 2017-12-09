<?php

namespace ShoppingCartBundle\Twig;


class ShoppingCartExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("starsAverageRating", [$this, "starsAverageRating"]),
            new \Twig_SimpleFilter("starsRating", [$this, "starsRating"])
        );
    }

    public function starsAverageRating($rating)
    {
        return str_repeat("<span class=\"fa fa-star checked\"></span>", $rating)
            . str_repeat("<span class=\"fa fa-star\" ></span>", 5 - $rating);
    }

    public function starsRating($rating)
    {
        return str_repeat("<span class=\"fa fa-star checked\"></span>", $rating);
    }
}