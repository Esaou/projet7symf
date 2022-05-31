<?php

namespace App\Controller\Customer;

use App\CustomException\FormErrorException;
use App\CustomException\ItemNotFoundException;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
     * @param Uuid $uuid
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     * @return Response
     */
    #[Route('/api/customers/{uuid}', name: 'edit_customer', methods: 'PUT')]
    public function __invoke(Uuid $uuid, Request $request, ValidatorInterface $validator, TranslatorInterface $translator): Response
    {
        $resellerConnected = $this->getUser();

        $customer = $this->customerRepository->findOneBy(['uuid' => $uuid, 'reseller' => $resellerConnected]);

        if (null === $customer) {
            throw new ItemNotFoundException($translator->trans('customer.not.found'));
        }

        /** @var Customer $customer */
        $customer = $this->serializer->deserialize($request->getContent(), Customer::class, 'json', ['object_to_populate' => $customer]);

        $errors = $validator->validate($customer);

        if (count($errors) !== 0) {
            throw new FormErrorException($errors);
        }

        $this->manager->persist($customer);
        $this->manager->flush();

        $message = $translator->trans('customer.update.client');
        $status = 200;


        return $this->json([
            'message' => $message,
        ], $status);
    }
}
