<?php

namespace App\Repository;

use App\Entity\CategoriePromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriePromo>
 *
 * @method CategoriePromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriePromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriePromo[]    findAll()
 * @method CategoriePromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriePromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriePromo::class);
    }

//    /**
//     * @return CategoriePromo[] Returns an array of CategoriePromo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategoriePromo
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
