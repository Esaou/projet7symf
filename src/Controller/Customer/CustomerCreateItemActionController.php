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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerCreateItemActionController extends AbstractController
{
    private CustomerRepository $customerRepository;

    private EntityManagerInterface $manager;

    private SerializerInterface $serializer;

    public function __construct(CustomerRepository $customerRepository, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->customerRepository = $customerRepository;
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    #[Route('/api/customers', name: 'add_customer', methods: 'POST')]
    public function __invoke(Request $request): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $message = 'Requête invalide.';
        $status = 400;

        $customer = $this->serializer->deserialize($request->getContent(), Customer::class, 'json');

        $firstname = $customer->getFirstname();
        $lastname = $customer->getLastname();
        $email = $customer->getEmail();

        if (null !== $firstname && null !== $email && null !== $lastname) {
            $customer->setReseller($resellerConnected);

            $this->manager->persist($customer);
            $this->manager->flush();

            $message = 'Client ajouté avec succès.';
            $status = 201;
        }

        return $this->json([
            'message' => $message,
        ], $status);
    }
}
