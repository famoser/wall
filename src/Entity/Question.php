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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ApiResource
 */
class Question extends BaseEntity
{
    use IdTrait;
    use TimeTrait;

    const REPETITION_NONE = 0;
    const REPETITION_DAILY = 1;

    /**
     * @var string
     * @Assert\NotBlank
     *
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var int
     * @Assert\Range(min=0, max=1)
     *
     * @ORM\Column(type="integer")
     */
    private $repetition;

    /**
     * @var Answers[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Answers", mappedBy="question", cascade={"remove"})
     */
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getRepetition(): int
    {
        return $this->repetition;
    }

    public function setRepetition(int $repetition): void
    {
        $this->repetition = $repetition;
    }

    /**
     * @return Answers[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }
}
