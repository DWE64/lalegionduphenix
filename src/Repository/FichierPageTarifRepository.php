<?php

namespace App\Repository;

use App\Entity\FichierPageTarif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FichierPageTarif|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichierPageTarif|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichierPageTarif[]    findAll()
 * @method FichierPageTarif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichierPageTarifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichierPageTarif::class);
    }

    // /**
    //  * @return FichierPageTarif[] Returns an array of FichierPageTarif objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FichierPageTarif
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
