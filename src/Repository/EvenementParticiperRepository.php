<?php

namespace App\Repository;

use App\Entity\EvenementParticiper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EvenementParticiper|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvenementParticiper|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvenementParticiper[]    findAll()
 * @method EvenementParticiper[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementParticiperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvenementParticiper::class);
    }

    // /**
    //  * @return EvenementParticiper[] Returns an array of EvenementParticiper objects
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
    public function findOneBySomeField($value): ?EvenementParticiper
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
