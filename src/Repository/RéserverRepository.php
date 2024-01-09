<?php

namespace App\Repository;

use App\Entity\Réserver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Réserver>
 *
 * @method Réserver|null find($id, $lockMode = null, $lockVersion = null)
 * @method Réserver|null findOneBy(array $criteria, array $orderBy = null)
 * @method Réserver[]    findAll()
 * @method Réserver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RéserverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Réserver::class);
    }

//    /**
//     * @return Réserver[] Returns an array of Réserver objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Réserver
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
