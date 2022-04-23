<?php

namespace App\Repository;

use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Phone>
 *
 * @method Phone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phone[]    findAll()
 * @method Phone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Phone $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Phone $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return array|int|string
     */
    public function findAllAsArray(): array|int|string
    {
        return $this->createQueryBuilder('phone')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param string $uuid
     * @return array|int|string
     */
    public function findAsArray(string $uuid): array|int|string
    {
        return $this->createQueryBuilder('phone')
            ->where('phone.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getArrayResult();
    }
}
