<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Entity\Reseller;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerUpdateItemActionController extends AbstractController
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

    /**
     * @ParamConverter("customer", converter="CustomerConverter")
     * @param Customer $customer
     * @param Request $request
     * @return Response
     */
    #[Route('/api/customers/{customer}', name: 'edit_customer', methods: 'PUT')]
    public function __invoke(Customer $customer, Request $request): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $message = 'Requête invalide.';
        $status = 400;

        /** @var Customer $customer */
        $customer = $this->serializer->deserialize($request->getContent(), $customer, 'json');

        $firstname = $customer->getFirstname();
        $lastname = $customer->getLastname();
        $email = $customer->getEmail();

        if (null !== $customer && $customer->getReseller() === $resellerConnected) {
            if (null !== $firstname) {
                $customer->setFirstname($firstname);
            }

            if (null !== $lastname) {
                $customer->setLastname($lastname);
            }

            if (null !== $email) {
                $customer->setEmail($email);
            }

            $this->manager->persist($customer);
            $this->manager->flush();

            $message = 'Client modifié avec succès.';
            $status = 200;
        }

        return $this->json([
            'message' => $message,
        ], $status);
    }
}
