<?php

/*
 * This file is part of the mangel.io project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Base\BaseEntity;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ApiResource
 */
class Product extends BaseEntity
{
    use IdTrait;
    use TimeTrait;

    const CATEGORY_FRUIT_VEGETABLES = 1;
    const CATEGORY_DAIRY_PRODUCTS_EGGS = 2;
    const CATEGORY_BREAD_BACKED_GOODS = 3;
    const CATEGORY_INVENTORIES = 4;
    const CATEGORY_FROZEN_FOOD_READY_MADE_MEALS = 5;
    const CATEGORY_DRINKS = 6;
    const CATEGORY_HOUSEHOLD = 7;

    /**
     * @var string
     * @Assert\NotBlank
     *
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @var int
     * @Assert\Range(min=1, max=7)
     *
     * @ORM\Column(type="integer")
     */
    private $category;

    /**
     * @var bool
     * @Assert\NotNull()
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function setCategory(int $category): void
    {
        $this->category = $category;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
