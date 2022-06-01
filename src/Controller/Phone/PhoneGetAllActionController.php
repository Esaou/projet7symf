<?php

namespace App\Controller\Phone;

use App\Entity\Customer;
use App\Entity\Phone;
use App\Repository\PhoneRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class PhoneGetAllActionController extends AbstractController
{
    /**
     * @param Paginator $paginator
     * @return Response
     */
    #[Route('/api/phones', name: 'get_phones', methods: 'GET')]
    public function __invoke(Paginator $paginator): Response
    {
        $paginator = $paginator->createPaginator(
            Phone::class,
            [],
            [],
            10,
            'get_phones'
        );

        return $this->json([
            '_pagination' => $paginator->getPagination(),
            'items' => $paginator->getDatas()
        ], 200, [], ['groups' => 'phone:read']);
    }
}
