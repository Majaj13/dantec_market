<?php

namespace App\Repository;
use App\Entity\User;
use App\Entity\Commandes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commandes>
 *
 * @method Commandes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commandes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commandes[]    findAll()
 * @method Commandes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commandes::class);
    }

    /**
     * Récupère la dernière commande non validée de l'utilisateur donné.
     *
     * @param User $user
     * @return Commandes|null
     */
    public function findDerniereNonValideeByUser(User $user): ?Commandes
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.valider = :val')
            ->andWhere('c.leUser = :user')
            ->setParameters([
                'val' => false,
                'user' => $user
            ])
            ->orderBy('c.dateCommande', 'DESC') // Tri par date de commande, du plus récent au plus ancien
            ->setMaxResults(1) // Limite les résultats à un seul
            ->getQuery()
            ->getOneOrNullResult(); // Récupère un seul résultat ou null si aucun résultat
    }

    public function getNonValidatedCommandesDetails(User $user)
{
    $qb = $this->createQueryBuilder('c')
        ->select('c.id','p.nomProduit', 'cmd.quantite', 'cmd.prixretenu', 
                 'cmd.quantite * cmd.prixretenu AS total',
                 'MIN(i.url) AS imageUrl')
        ->leftJoin('c.lesCommandes', 'cmd')
        ->leftJoin('cmd.leProduit', 'p')
        ->leftJoin('p.lesImages', 'i')
        ->where('c.valider = :valider')
        ->andWhere('c.leUser = :user')
        ->setParameter('valider', false)
        ->setParameter('user', $user)
        ->groupBy('cmd.id') // Assurez-vous que cela correspond à vos besoins
        ->orderBy('c.dateCommande', 'ASC'); // ou un autre critère de tri si nécessaire

    return $qb->getQuery()->getResult();
}
public function findValidatedOrdersByUser(User $user)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.leUser = :user')
           ->andWhere('c.valider = :validated')
           ->setParameter('user', $user)
           ->setParameter('validated', true)
           ->orderBy('c.dateCommande', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function getValidatedCommandesDetails(int $id)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.id','p.id AS pid', 'p.nomProduit', 'cmd.quantite', 'cmd.prixretenu', 
                     'cmd.quantite * cmd.prixretenu AS total','p.etoiles','cmd.noteDonnee',
                     'MIN(i.url) AS imageUrl')
            ->leftJoin('c.lesCommandes', 'cmd')
            ->leftJoin('cmd.leProduit', 'p')
            ->leftJoin('p.lesImages', 'i')
            ->where('c.valider = :valider')
            ->andWhere('c.id = :id')
            ->setParameter('valider', true) // Changé en true pour sélectionner les commandes validées
            ->setParameter('id', $id)
            ->groupBy('cmd.id') // Assurez-vous que cela correspond à vos besoins
            ->orderBy('c.dateCommande', 'ASC'); // ou un autre critère de tri si nécessaire
    
        return $qb->getQuery()->getResult();
    }
    

//    /**
//     * @return Commandes[] Returns an array of Commandes objects
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

//    public function findOneBySomeField($value): ?Commandes
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
