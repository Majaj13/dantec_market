<?php

namespace App\Repository;

use App\Entity\Favoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favoris>
 *
 * @method Favoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favoris[]    findAll()
 * @method Favoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favoris::class);
    }

    public function getListeFavoris($user)
    {
        // Création du QueryBuilder
        $qb = $this->createQueryBuilder('f')
            ->select('p.id', 'p.nomProduit', 'p.prix', 'MIN(i.url) AS imageUrl')
            ->innerJoin('f.leProduit', 'p')
            ->leftJoin('p.lesImages', 'i')
            ->where('f.leUser = :user')
            ->setParameter('user', $user)
            ->groupBy('p.id')
            ->orderBy('p.id', 'ASC');

        // Exécution de la requête et retour des résultats
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Favoris[] Returns an array of Favoris objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Favoris
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
