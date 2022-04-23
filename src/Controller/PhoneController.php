<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AbstractController
{
    #[Route('/api/phones', name: 'get_phones', methods: 'GET')]
    public function getPhones(): Response
    {
        return $this->json([
            'message' => 'Tous les phones.',
        ]);
    }

    #[Route('/api/phones/{idPhone}', name: 'get_phone', methods: 'GET')]
    public function getPhone(int $idPhone): Response
    {
        return $this->json([
            'message' => "Détail d'un phone.",
        ]);
    }
}
