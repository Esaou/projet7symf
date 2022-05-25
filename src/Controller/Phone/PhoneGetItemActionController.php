<?php

namespace App\Controller\Phone;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class PhoneGetItemActionController extends AbstractController
{
    public function __construct(private PhoneRepository $phoneRepository) {

    }

    /**
     * @param Uuid $uuid
     * @return Response
     */
    #[Route('/api/phones/{uuid}', name: 'get_phone', methods: 'GET')]
    public function _invoke(Uuid $uuid): Response
    {
        $phone = $this->phoneRepository->findOneBy(['uuid' => $uuid]);

        return $this->json($phone, 200, [], ['groups' => 'phone:read']);
    }
}
