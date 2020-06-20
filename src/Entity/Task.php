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
class Task extends BaseEntity
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
     * @Assert\Range(min=1, max=356)
     *
     * @ORM\Column(type="integer")
     */
    private $intervalInDays;

    /**
     * @var int
     * @Assert\NotBlank
     *
     * @ORM\Column(type="integer")
     */
    private $reward;

    /**
     * @var \DateTime
     * @Assert\NotBlank
     *
     * @ORM\Column(type="datetime")
     */
    private $lastExecutionAt;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIntervalInDays(): int
    {
        return $this->intervalInDays;
    }

    public function setIntervalInDays(int $intervalInDays): void
    {
        $this->intervalInDays = $intervalInDays;
    }

    public function getReward(): int
    {
        return $this->reward;
    }

    public function setReward(int $reward): void
    {
        $this->reward = $reward;
    }
}
