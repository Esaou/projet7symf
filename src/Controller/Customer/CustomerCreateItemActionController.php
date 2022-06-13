<?php

namespace App\Controller\Customer;

use App\CustomException\FormErrorException;
use App\Entity\Customer;
use App\Entity\Reseller;
use App\Repository\CustomerRepository;
use App\Service\BodyValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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

    /**
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param BodyValidator $bodyValidator
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/api/customers', name: 'add_customer', methods: 'POST')]
    public function __invoke(Request $request, TranslatorInterface $translator, BodyValidator $bodyValidator, ValidatorInterface $validator): Response
    {
        $bodyValidator->bodyValidate($request->getContent());

        /** @var Reseller $resellerConnected */
        $resellerConnected = $this->getUser();

        $customer = $this->serializer->deserialize($request->getContent(), Customer::class, 'json');

        $errors = $validator->validate($customer);

        if (count($errors) !== 0) {
            throw new FormErrorException($errors);
        }

        $customer->setReseller($resellerConnected);

        $this->manager->persist($customer);
        $this->manager->flush();

        $message = $translator->trans('customer.add.client');
        $status = 201;

        return $this->json([
            'message' => $message,
        ], $status);
    }
}
