<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Entity\Reseller;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class CustomerGetItemActionController extends AbstractController
{
    private CustomerRepository $customerRepository;

    private EntityManagerInterface $manager;

    public function __construct(CustomerRepository $customerRepository, EntityManagerInterface $manager)
    {
        $this->customerRepository = $customerRepository;
        $this->manager = $manager;
    }

    /**
     * @param Uuid $uuid
     * @return Response
     */
    #[Route('/api/customers/{uuid}', name: 'get_customer', methods: 'GET')]
    public function __invoke(Uuid $uuid): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $customer = $this->customerRepository->findOneBy(['uuid' => $uuid, 'reseller' => $resellerConnected]);

        return $this->json($customer, 200, [], ['groups' => 'customer:read']);
    }
}
