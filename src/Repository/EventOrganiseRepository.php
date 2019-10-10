<?php

namespace App\Repository;

use App\Entity\EventOrganise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EventOrganise|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventOrganise|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventOrganise[]    findAll()
 * @method EventOrganise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventOrganiseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventOrganise::class);
    }

    // /**
    //  * @return EventOrganise[] Returns an array of EventOrganise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventOrganise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
