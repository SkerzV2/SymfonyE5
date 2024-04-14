<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class TokenController extends AbstractController
{
    public function createToken(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    {
        $token = $JWTManager->create($user);

        return $this->json(['token' => $token]);
    }
}

