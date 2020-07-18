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

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use App\Entity\Base\BaseEntity;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ApiResource
 * @ApiFilter(DateFilter::class, properties={"startAt"}))
 */
class Event extends BaseEntity
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
     * @var \DateTime
     * @Assert\NotBlank
     *
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }
}
