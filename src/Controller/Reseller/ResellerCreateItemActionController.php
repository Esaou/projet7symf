<?php

namespace App\Controller\Reseller;

use App\Entity\Reseller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ResellerCreateItemActionController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/api/register', name: 'api_register', methods: 'POST')]
    public function __invoke(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $message = 'Requête invalide.';
        $status = 400;

        /** @var Reseller $reseller */
        $reseller = $this->serializer->deserialize($request->getContent(), Reseller::class, 'json');

        $name = $reseller->getName();
        $email = $reseller->getEmail();
        $password = $reseller->getPassword();

        if (null !== $name && null !== $email && null !== $password) {
            $password = $passwordHasher->hashPassword($reseller, $password);
            $reseller->setPassword($password);

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
