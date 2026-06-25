<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class UserMeController extends AbstractController
{

    public function __invoke(SerializerInterface $serializer): Response
    {
        return new Response($serializer->serialize(
            $this->getUser(),
            'json',
            ['groups' => 'user:item']
        ));
    }

}
