<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadProducts($manager);
        $this->loadEvents($manager);

        $manager->flush();
    }

    private function loadProducts(ObjectManager $manager)
    {
        $products = [
            'Tomato' => Product::CATEGORY_FRUIT_VEGETABLES,
            'Paprika' => Product::CATEGORY_FRUIT_VEGETABLES,
            'Apple' => Product::CATEGORY_FRUIT_VEGETABLES,
            'Banana' => Product::CATEGORY_FRUIT_VEGETABLES,
            'Croissant' => Product::CATEGORY_BREAD_BACKED_GOODS,
            'Toast' => Product::CATEGORY_BREAD_BACKED_GOODS,
            'Milk' => Product::CATEGORY_DAIRY_PRODUCTS_EGGS,
            'Camenbert' => Product::CATEGORY_DAIRY_PRODUCTS_EGGS,
            'Grana Padano' => Product::CATEGORY_DAIRY_PRODUCTS_EGGS,
            'Eggs' => Product::CATEGORY_DAIRY_PRODUCTS_EGGS,
            'Ice' => Product::CATEGORY_FROZEN_FOOD_READY_MADE_MEALS,
            'Pasta' => Product::CATEGORY_FROZEN_FOOD_READY_MADE_MEALS,
            'Spaghetti' => Product::CATEGORY_INVENTORIES,
            'Rice' => Product::CATEGORY_INVENTORIES,
            'Tomato Sauce' => Product::CATEGORY_INVENTORIES,
            'Beer' => Product::CATEGORY_DRINKS,
            'White Wine' => Product::CATEGORY_DRINKS,
            'Shampoo' => Product::CATEGORY_HOUSEHOLD,
        ];

        foreach ($products as $name => $category) {
            $product = new Product();
            $product->setName($name);
            $product->setCategory($category);
            $product->setActive(0 === rand() % 3);
            $manager->persist($product);
        }
    }

    private function loadEvents(ObjectManager $manager)
    {
        $events = [
            'Pasta Fun' => new \DateTime('today + 1 day'),
            'Safe & Clean' => new \DateTime('today + 7 day'),
            'Birthday' => new \DateTime('today + 18 day'),
        ];

        foreach ($events as $name => $date) {
            $event = new Event();
            $event->setName($name);
            $event->setStartAt($date);

            $manager->persist($event);
        }
    }
}
