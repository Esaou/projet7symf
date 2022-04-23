<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    #[Route('/api/customers', name: 'get_customers', methods: 'GET')]
    public function getCustomers(): Response
    {
        return $this->json([
            'message' => 'Tous mes customers.',
        ]);
    }

    #[Route('/api/customers', name: 'add_customer', methods: 'POST')]
    public function addCustomer(): Response
    {
        return $this->json([
            'message' => 'Ajouter un customer.',
        ]);
    }

    #[Route('/api/customers/{idCustomer}', name: 'edit_customer', methods: 'PUT')]
    public function updateCustomer(int $idCustomer): Response
    {
        return $this->json([
            'message' => 'Modifier un customer.',
        ]);
    }

    #[Route('/api/customers/{idCustomer}', name: 'delete_customer', methods: 'DELETE')]
    public function deleteCustomer(int $idCustomer): Response
    {
        return $this->json([
            'message' => 'Supprimer un customer.',
        ]);
    }
}
