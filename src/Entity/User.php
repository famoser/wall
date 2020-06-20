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
class User extends BaseEntity
{
    use IdTrait;
    use TimeTrait;

    /**
     * @var string
     * @Assert\NotBlank
     *
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @var int
     * @Assert\Range(min=1000, max=99999999)
     *
     * @ORM\Column(type="integer")
     */
    private $pin;

    /**
     * @var int
     * @Assert\Range(min=0, max=200000)
     *
     * @ORM\Column(type="integer")
     */
    private $karma;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPin(): int
    {
        return $this->pin;
    }

    public function setPin(int $pin): void
    {
        $this->pin = $pin;
    }

    public function getKarma(): int
    {
        return $this->karma;
    }

    public function setKarma(int $karma): void
    {
        $this->karma = $karma;
    }
}
