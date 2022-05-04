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

class CustomerGetItemActionController extends AbstractController
{
    private CustomerRepository $customerRepository;

    private EntityManagerInterface $manager;

    public function __construct(CustomerRepository $customerRepository, EntityManagerInterface $manager)
    {
        $this->customerRepository = $customerRepository;
        $this->manager = $manager;
    }

    #[Route('/api/customers/{customer}', name: 'get_customer', methods: 'GET')]
    public function __invoke(Customer $customer): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        if ($customer->getReseller() !== $resellerConnected) {
            throw new NotFoundHttpException('Client introuvable.');
        }

        return $this->json($customer, 200, [], ['groups' => 'customer:read']);
    }
}
