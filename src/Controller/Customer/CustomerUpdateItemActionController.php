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
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @param Uuid $uuid
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response
     */
    #[Route('/api/customers/{uuid}', name: 'edit_customer', methods: 'PUT')]
    public function __invoke(Uuid $uuid, Request $request, TranslatorInterface $translator): Response
    {
        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $customer = $this->customerRepository->findOneBy(['uuid' => $uuid]);

        $message = $translator->trans('invalid.request');
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

            $message = $translator->trans('customer.update.client');
            $status = 200;
        }

        return $this->json([
            'message' => $message,
        ], $status);
    }
}
