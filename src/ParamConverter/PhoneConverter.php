<?php


namespace App\ParamConverter;

use App\Repository\PhoneRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

class PhoneConverter implements ParamConverterInterface
{
    private PhoneRepository $phoneRepository;

    public function __construct(PhoneRepository $phoneRepository)
    {
        $this->phoneRepository = $phoneRepository;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $uuid = $request->get('phone');

        $uuid = new Uuid($uuid);
        $uuid = $uuid->toBinary();

        $customer = $this->phoneRepository
            ->createQueryBuilder('phone')
            ->where('phone.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getSingleResult();

        $request->attributes->set($configuration->getName(), $customer);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === 'App\Entity\Phone';
    }
}