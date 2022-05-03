<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

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
     * @param Uuid $uuid
     * @param PhoneRepository $phoneRepository
     * @return Response
     */
    #[Route('/api/phones/{uuid}', name: 'get_phone', methods: 'GET')]
    public function getPhone(Uuid $uuid, PhoneRepository $phoneRepository): Response
    {
        $phone = $phoneRepository->findAsArray($uuid);

        return $this->json($phone);
    }
}
