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
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "DELETE"},
 *     normalizationContext={"groups"={"answer"}},
 *     denormalizationContext={"groups"={"answer"}}),
 * )
 */
class Answer extends BaseEntity
{
    use IdTrait;
    use TimeTrait;

    const VALUE_YES = 1;
    const VALUE_NO = 2;

    /**
     * @var int
     * @Assert\NotBlank
     * @Groups("answer")
     *
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @var DateTime
     * @Assert\NotBlank
     * @Groups("answer")
     *
     * @ORM\Column(type="datetime")
     */
    private $givenAt;

    /**
     * @var Question
     * @Assert\NotBlank
     * @Groups("answer")
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers")
     */
    private $question;

    /**
     * @var User
     * @Assert\NotBlank
     * @Groups("answer")
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="answers")
     */
    private $user;

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getGivenAt(): DateTime
    {
        return $this->givenAt;
    }

    public function setGivenAt(DateTime $givenAt): void
    {
        $this->givenAt = $givenAt;
    }
}
