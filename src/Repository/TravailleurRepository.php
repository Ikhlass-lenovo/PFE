<?php

namespace App\Repository;

use App\Entity\Travailleur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Travailleur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Travailleur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Travailleur[]    findAll()
 * @method Travailleur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravailleurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Travailleur::class);
    }

    // /**
    //  * @return Travailleur[] Returns an array of Travailleur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Travailleur
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
