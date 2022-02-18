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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *     normalizationContext={"skip_null_values" = false,"groups"={"task"}},
 *     denormalizationContext={"groups"={"task"}}),
 * )
 */
class Task extends BaseEntity
{
    use IdTrait;
    use TimeTrait;

    /**
     * @var string
     * @Assert\NotBlank
     * @Groups("task")
     *
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @var int
     * @Assert\Range(min=1, max=356)
     * @Groups("task")
     *
     * @ORM\Column(type="integer")
     */
    private $intervalInDays;

    /**
     * @var int
     * @Assert\NotBlank
     * @Groups("task")
     *
     * @ORM\Column(type="integer")
     */
    private $reward;

    /**
     * @var \DateTime|null
     * @Groups("task")
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastExecutionAt = null;

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

    public function getLastExecutionAt(): ?\DateTime
    {
        return $this->lastExecutionAt;
    }

    public function setLastExecutionAt(?\DateTime $lastExecutionAt): void
    {
        $this->lastExecutionAt = $lastExecutionAt;
    }
}
