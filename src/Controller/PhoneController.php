<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AbstractController
{
    /**
     * @param PhoneRepository $phoneRepository
     * @return Response
     */
    #[Route('/api/phones', name: 'get_phones', methods: 'GET')]
    public function getPhones(PhoneRepository $phoneRepository): Response
    {
        $phones = $phoneRepository->findAllAsArray();

        return $this->json($phones);
    }

    /**
     * @param int $idPhone
     * @param PhoneRepository $phoneRepository
     * @return Response
     */
    #[Route('/api/phones/{uuidPhone}', name: 'get_phone', methods: 'GET')]
    public function getPhone(string $uuidPhone, PhoneRepository $phoneRepository): Response
    {
        $phone = $phoneRepository->findAsArray($uuidPhone);

        return $this->json($phone);
    }
}
