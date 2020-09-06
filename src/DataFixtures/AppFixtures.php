<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Embed;
use App\Entity\Event;
use App\Entity\Product;
use App\Entity\Question;
use App\Entity\Setting;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadEvents($manager);
        $this->loadProducts($manager);
        $this->loadEmbed($manager);
        $users = $this->loadUsers($manager);
        $questions = $this->loadQuestions($manager);
        $this->loadAnswers($manager, $questions, $users);
        $this->loadSetting($manager);
        $this->loadTask($manager);

        $manager->flush();
    }

    private function loadEvents(ObjectManager $manager)
    {
        $events = [
            'Pasta Fun' => new \DateTime('today + 1 day + 19 hours'),
            'Safe & Clean' => new \DateTime('today + 7 day + 18 hours'),
            'Birthday' => new \DateTime('today + 18 day'),
        ];

        foreach ($events as $name => $date) {
            $event = new Event();
            $event->setName($name);
            $event->setStartAt($date);

            $manager->persist($event);
        }
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

    private function loadQuestions(ObjectManager $manager)
    {
        $events = [
            'Lunch?' => Question::REPETITION_DAILY,
            'Dinner?' => Question::REPETITION_DAILY,
            'Hotel?' => Question::REPETITION_NONE,
        ];

        $questions = [];
        foreach ($events as $name => $repetition) {
            $question = new Question();
            $question->setText($name);
            $question->setRepetition($repetition);

            $manager->persist($question);

            $questions[] = $question;
        }

        return $questions;
    }

    /**
     * @param Question[] $questions
     * @param User[]     $users
     */
    private function loadAnswers(ObjectManager $manager, array $questions, array $users)
    {
        $userIndex = 0;

        foreach ($questions as $question) {
            $user = $users[$userIndex];

            $answer = new Answer();
            $answer->setValue(0 == $userIndex % 2 ? Answer::VALUE_YES : Answer::VALUE_NO);
            $answer->setGivenAt(new \DateTime());
            $answer->setQuestion($question);
            $answer->setUser($user);

            $manager->persist($answer);

            if (++$userIndex > count($users) - 1) {
                $userIndex = 0;
            }
        }
    }

    private function loadUsers(ObjectManager $manager)
    {
        $template = [
            'Florian' => 300,
            'Cédric' => 101,
            'Xenia' => 200,
        ];

        $users = [];
        foreach ($template as $name => $karma) {
            $user = new User();
            $user->setName($name);
            $user->setKarma($karma);
            $pin = floor(sin($karma) * 10000);
            $user->setPin($pin);

            $manager->persist($user);

            $users[] = $user;
        }

        return $users;
    }

    private function loadSetting(ObjectManager $manager)
    {
        $events = [
            Setting::KEY_DEPARTURE => 'Waserstrasse',
        ];

        foreach ($events as $key => $value) {
            $event = new Setting();
            $event->setKey($key);
            $event->setValue($value);

            $manager->persist($event);
        }
    }

    private function loadEmbed(ObjectManager $manager)
    {
        $embeds = [
            'https://via.placeholder.com/300C' => Embed::TYPE_IMAGE,
            'https://via.placeholder.com/150x400' => Embed::TYPE_IMAGE,
            'https://via.placeholder.com/200C' => Embed::TYPE_IMAGE,
            'https://via.placeholder.com/850x400' => Embed::TYPE_IMAGE,
            'https://via.placeholder.com/250x400' => Embed::TYPE_IMAGE,
            'https://via.placeholder.com/850x500' => Embed::TYPE_IMAGE,
            'https://via.placeholder.com/650x900' => Embed::TYPE_IMAGE,
            'https://via.placeholder.com/350x300' => Embed::TYPE_IMAGE,
            'https://img-9gag-fun.9cache.com/photo/a9nqRA1_460svvp9.webm' => Embed::TYPE_VIDEO,
            'Hold the vision, trust the process.' => Embed::TYPE_QUOTE,
            'https://www.youtube.com/watch?v=gOidsqd4OKo' => Embed::TYPE_YOUTUBE,
        ];

        foreach ($embeds as $content => $type) {
            $event = new Embed();
            $event->setContent($content);
            $event->setType($type);

            $manager->persist($event);
        }
    }

    private function loadTask(ObjectManager $manager)
    {
        $events = [
            'Clean the kitchen' => 7,
            'Mop the floor' => 14,
            'Vacuum' => 7,
        ];

        $index = 0;
        foreach ($events as $name => $intervalInDays) {
            $event = new Task();
            $event->setName($name);
            $event->setIntervalInDays($intervalInDays);
            $event->setReward(strlen($name));
            $event->setLastExecutionAt(new \DateTime('today - '.$index++.' days'));

            $manager->persist($event);
        }
    }
}
