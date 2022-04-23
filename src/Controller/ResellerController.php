<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResellerController extends AbstractController
{
    #[Route('/api/reseller/register', name: 'reseller_register', methods: 'POST')]
    public function register(): Response
    {
        return $this->json([
            'message' => 'Register.',
        ]);
    }

    #[Route('/api/reseller/login', name: 'reseller_login', methods: 'POST')]
    public function login(): Response
    {
        return $this->json([
            'message' => 'Login.',
        ]);
    }
}
