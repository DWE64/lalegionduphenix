<?php

namespace App\Repository;

use App\Entity\InformationPageTarif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InformationPageTarif|null find($id, $lockMode = null, $lockVersion = null)
 * @method InformationPageTarif|null findOneBy(array $criteria, array $orderBy = null)
 * @method InformationPageTarif[]    findAll()
 * @method InformationPageTarif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InformationPageTarifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InformationPageTarif::class);
    }

    // /**
    //  * @return InformationPageTarif[] Returns an array of InformationPageTarif objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InformationPageTarif
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
}
