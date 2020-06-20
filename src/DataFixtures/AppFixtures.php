<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setName('Paprika');
        $product->setCategory(Product::CATEGORY_FRUIT_VEGETABLES);
        $manager->persist($product);

        $manager->flush();
    }
}
