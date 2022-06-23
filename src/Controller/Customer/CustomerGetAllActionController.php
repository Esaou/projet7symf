<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Entity\Figure;
use App\Entity\Reseller;
use App\Repository\CustomerRepository;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerGetAllActionController extends AbstractController
{
    private CustomerRepository $customerRepository;

    private EntityManagerInterface $manager;

    private Paginator $paginator;

    public function __construct(Paginator $paginator, CustomerRepository $customerRepository, EntityManagerInterface $manager)
    {
        $this->customerRepository = $customerRepository;
        $this->manager = $manager;
        $this->paginator = $paginator;
    }

    /**
     * @return Response
     */
    #[Route('/api/customers', name: 'get_customers', methods: 'GET')]
    public function __invoke(): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $paginator = $this->paginator->createPaginator(Customer::class,
            ['reseller' => $resellerConnected],
            ['createdAt'=>'desc'],
            10,
            'get_customers'
        );

        return $this->json($paginator, 200, [], [
            'callbacks' => [
                'reseller' => function($resellerConnected) {
                    return $resellerConnected->getName();
                }
            ]
        ]);
    }
}
