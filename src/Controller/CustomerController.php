<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Reseller;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private CustomerRepository $customerRepository;

    private EntityManagerInterface $manager;

    public function __construct(CustomerRepository $customerRepository, EntityManagerInterface $manager)
    {
        $this->customerRepository = $customerRepository;
        $this->manager = $manager;
    }

    #[Route('/api/customers', name: 'get_customers', methods: 'GET')]
    public function getCustomers(): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $customers = $this->customerRepository->findCustomersByReseller($resellerConnected);

        return $this->json($customers);
    }

    #[Route('/api/customers/{uuidCustomer}', name: 'get_customer', methods: 'GET')]
    public function getCustomer(string $uuidCustomer): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $customers = $this->customerRepository->findCustomerOfReceller($uuidCustomer, $resellerConnected);

        return $this->json($customers);
    }

    #[Route('/api/customers', name: 'add_customer', methods: 'POST')]
    public function addCustomer(Request $request): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $message = 'Requête invalide.';
        $status = 400;

        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if (null !== $firstname && null !== $email && null !== $lastname && null !== $password) {
            $customer = new Customer();
            $customer
                ->setEmail($email)
                ->setFirstname($firstname)
                ->setLastname($lastname)
                ->setPassword($password)
                ->setReseller($resellerConnected)
            ;

            $this->manager->persist($customer);
            $this->manager->flush();

            $message = 'Client ajouté avec succès.';
            $status = 201;
        }

        return $this->json([
            'message' => $message,
        ], $status);
    }

    #[Route('/api/customers/{uuidCustomer}', name: 'edit_customer', methods: 'PUT')]
    public function updateCustomer(string $uuidCustomer, Request $request): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $customer = $this->customerRepository->findOneBy(['uuid' => $uuidCustomer]);

        $message = 'Requête invalide.';
        $status = 400;

        $firstname = $request->query->get('firstname');
        $lastname = $request->query->get('lastname');
        $email = $request->query->get('email');
        $password = $request->query->get('password');


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

            if (null !== $password) {
                $customer->setPassword($password);
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

    #[Route('/api/customers/{uuidCustomer}', name: 'delete_customer', methods: 'DELETE')]
    public function deleteCustomer(string $uuidCustomer): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $customer = $this->customerRepository->findOneBy(['uuid' => $uuidCustomer]);

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
