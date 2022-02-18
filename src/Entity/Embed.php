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
 *     normalizationContext={"groups"={"embed"}},
 *     denormalizationContext={"groups"={"embed"}},
 *     attributes={"order"={"createdAt": "ASC"}}
 * )
 */
class Embed extends BaseEntity
{
    use IdTrait;
    use TimeTrait;

    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_QUOTE = 3;
    const TYPE_YOUTUBE = 4;

    /**
     * @var string
     * @Assert\NotBlank
     * @Groups("embed")
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var int
     * @Assert\Range(min=1, max=4)
     * @Groups("embed")
     *
     * @ORM\Column(type="integer")
     */
    private $type;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }
}
