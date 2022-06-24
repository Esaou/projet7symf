<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Figure;
use App\Entity\Reseller;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * @param Customer $entity
     * @param bool $flush
     */
    public function add(Customer $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Customer $entity
     * @param bool $flush
     */
    public function remove(Customer $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Reseller $reseller
     * @return array|int|string
     */
    public function findCustomersByReseller(Reseller $reseller): array|int|string
    {
        return $this->createQueryBuilder('customer')
            ->where('customer.reseller = :reseller')
            ->setParameter('reseller', $reseller->getId())
            ->getQuery()
            ->getResult();
    }

    public function getCustomerByReseller(Customer $customer, Reseller $reseller): mixed
    {
        $query = $this->createQueryBuilder('customer')
            ->select('count(customer.id)')
            ->where('customer.email = :email')
            ->setParameter('email', $customer->getEmail())
            ->andWhere('customer.reseller = :resellerId')
            ->setParameter('resellerId', $reseller->getId());

        if (null !== $customer->getId()) {
            $query = $query
                ->andWhere('customer.id != :id')
                ->setParameter('id', $customer->getId());
        }

        $count = $query
            ->getQuery()
            ->getSingleScalarResult();

        return $count;
    }
}
