<?php

namespace App\Controller\Phone;

use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class PhoneGetAllActionController extends AbstractController
{
    /**
     * @param PhoneRepository $phoneRepository
     * @return Response
     */
    #[Route('/api/phones', name: 'get_phones', methods: 'GET')]
    public function __invoke(PhoneRepository $phoneRepository): Response
    {
        $phones = $phoneRepository->findAll();

        return $this->json($phones, 200, [], ['groups' => 'phone:read']);
    }
}
