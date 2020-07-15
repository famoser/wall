<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserRewardAction extends AbstractController
{
    public function __invoke(User $data)
    {
        $manager = $this->getDoctrine()->getManager();

        $rewardKarma = $data->getKarma();

        $manager->refresh($data);
        $data->setKarma($data->getKarma() + $rewardKarma);

        $manager->persist($data);
        $manager->flush();

        return $data;
    }
}
