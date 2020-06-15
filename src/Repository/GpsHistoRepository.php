<?php

namespace App\Repository;

use App\Entity\GpsHisto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GpsHisto|null find($id, $lockMode = null, $lockVersion = null)
 * @method GpsHisto|null findOneBy(array $criteria, array $orderBy = null)
 * @method GpsHisto[]    findAll()
 * @method GpsHisto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GpsHistoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GpsHisto::class);
    }

    // /**
    //  * @return GpsHisto[] Returns an array of GpsHisto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GpsHisto
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
