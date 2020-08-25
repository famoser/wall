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
use App\Controller\UserRewardAction;
use App\Entity\Base\BaseEntity;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *     attributes={"pagination_enabled"=false},
 *     collectionOperations={"get", "post"},
 *     normalizationContext={"groups"={"user-read"}},
 *     denormalizationContext={"groups"={"user-write"}},
 *     itemOperations={
 *       "GET",
 *       "patch_user_reward"={
 *         "method"="patch",
 *         "path"="/users/{id}/reward",
 *         "controller"= UserRewardAction::class,
 *         "denormalization_context"={"groups"={"user-reward"}},
 *       }
 *     }
 *  )
 */
class User extends BaseEntity
{
    use IdTrait;
    use TimeTrait;

    /**
     * @var string
     * @Assert\NotBlank
     * @Groups({"user-read", "user-write"})
     *
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @var int
     * @Assert\NotBlank
     * @Groups({"user-read", "user-write"})
     *
     * @ORM\Column(type="integer")
     */
    private $pin;

    /**
     * @var int
     * @Assert\Range(min=0, max=200000)
     * @Groups({"user-reward", "user-read"})
     *
     * @ORM\Column(type="integer")
     */
    private $karma = 0;

    /**
     * @var Answer[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="user", cascade={"remove"})
     */
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

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

    /**
     * @return Answer[]|ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}
