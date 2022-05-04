<?php

namespace App\Controller\Phone;

use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneGetItemActionController extends AbstractController
{
    /**
     * @param Phone $phone
     * @return Response
     */
    #[Route('/api/phones/{phone}', name: 'get_phone', methods: 'GET')]
    public function _invoke(Phone $phone): Response
    {
        return $this->json($phone, 200, [], ['groups' => 'phone:read']);
    }
}
