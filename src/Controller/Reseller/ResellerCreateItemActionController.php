<?php

namespace App\Controller\Reseller;

use App\CustomException\FormErrorException;
use App\Entity\Reseller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResellerCreateItemActionController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/api/register', name: 'api_register', methods: 'POST')]
    public function __invoke(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher,ValidatorInterface $validator, TranslatorInterface $translator): Response
    {
        /** @var Reseller $reseller */
        $reseller = $this->serializer->deserialize($request->getContent(), Reseller::class, 'json');

        $errors = $validator->validate($reseller);

        if (count($errors) !== 0) {
            throw new FormErrorException($errors);
        }

        $password = $passwordHasher->hashPassword($reseller, $reseller->getPassword());
        $reseller->setPassword($password);

        $manager->persist($reseller);
        $manager->flush();

        $messages = $translator->trans('reseller.add');
        $status = 201;

        return $this->json([
            'messages' => $messages,
        ], $status);
    }
}
