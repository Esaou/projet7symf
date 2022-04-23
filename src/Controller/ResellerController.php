<?php

namespace App\Controller;

use App\Entity\Reseller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResellerController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: 'POST')]
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $message = 'Requête invalide.';
        $status = 400;

        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if (null !== $name && null !== $email && null !== $password) {
            $reseller = new Reseller();
            $password = $passwordHasher->hashPassword($reseller, $password);
            $reseller
                ->setEmail($email)
                ->setName($name)
                ->setPassword($password);

            $manager->persist($reseller);
            $manager->flush();

            $message = 'Utilisateur créé avec succès.';
            $status = 201;
        }

        return $this->json([
            'message' => $message,
        ], $status);
    }
}
