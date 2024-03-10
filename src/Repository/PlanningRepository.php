<?php

namespace App\Repository;

use App\Entity\Planning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Planning>
 *
 * @method Planning|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planning|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planning[]    findAll()
 * @method Planning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning::class);
    }

    public function trouverHorairesSemaineCourante(): array
{
    // Obtenir l'objet DateTime pour aujourd'hui et pour le dernier jour de la semaine (dimanche)
    $aujourdhui = new \DateTime();
    $finDeSemaine = (new \DateTime())->modify('sunday this week');

    // Créer le QueryBuilder
    $qb = $this->createQueryBuilder('p')
        ->where('p.jour >= :aujourdhui AND p.jour <= :finDeSemaine')
        ->setParameter('aujourdhui', $aujourdhui->format('Y-m-d'))
        ->setParameter('finDeSemaine', $finDeSemaine->format('Y-m-d'))
        ->orderBy('p.jour', 'ASC')
        ->addOrderBy('p.heureDebut', 'ASC');

    // Retourner les résultats de la requête
    return $qb->getQuery()->getResult();
}

//    /**
//     * @return Planning[] Returns an array of Planning objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Planning
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
