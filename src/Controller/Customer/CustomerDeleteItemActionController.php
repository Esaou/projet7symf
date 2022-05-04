<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Entity\Reseller;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerDeleteItemActionController extends AbstractController
{
    private CustomerRepository $customerRepository;

    private EntityManagerInterface $manager;

    public function __construct(CustomerRepository $customerRepository, EntityManagerInterface $manager)
    {
        $this->customerRepository = $customerRepository;
        $this->manager = $manager;
    }

    #[Route('/api/customers/{customer}', name: 'delete_customer', methods: 'DELETE')]
    public function __invoke(Customer $customer): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $message = 'Client introuvable.';
        $status = 400;

        if (null !== $customer && $customer->getReseller() === $resellerConnected) {
            $this->manager->remove($customer);
            $this->manager->flush();

            $message = 'Client supprimé avec succès.';
            $status = 200;
        }

        return $this->json([
            'message' => $message,
        ], $status);
    }
}
