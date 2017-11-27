<?php

namespace ShoppingCartBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = $this->container->get('fidry_alice_data_fixtures.loader.doctrine');
        $loader->load([__DIR__ . '/fixtures.yml']);
    }
}