<?php

namespace App\ParamConverter;

use App\Repository\CustomerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

class CustomerConverter implements ParamConverterInterface
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $uuid = $request->get('customer');

        $uuid = new Uuid($uuid);
        $uuid = $uuid->toBinary();

        $customer = $this->customerRepository
            ->createQueryBuilder('customer')
            ->where('customer.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getSingleResult();

        $request->attributes->set($configuration->getName(), $customer);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === 'App\Entity\Customer';
    }
}