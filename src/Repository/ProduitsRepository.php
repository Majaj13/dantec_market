<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @extends ServiceEntityRepository<Produits>
 *
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }

    public function findTopVendusLastWeek()
    {
        // Date d'il y a une semaine
        $dateIlYaUneSemaine = new \DateTime('-1 week');

        // Création du Query Builder
        $qb = $this->createQueryBuilder('p')
        ->select('p.id,p.nomProduit, p.prix, SUM(c.quantite) as quantite_vendue, MIN(i.url) as imageUrl')
        ->join('App\Entity\Commander', 'c', Join::WITH, 'p.id = c.leProduit')
        ->join('App\Entity\Commandes', 'co', Join::WITH, 'c.laCommande = co.id')
        ->leftJoin('p.lesImages', 'i') // Modifié ici
        ->where('co.dateCommande > :dateIlYaUneSemaine')
        ->setParameter('dateIlYaUneSemaine', $dateIlYaUneSemaine)
        ->groupBy('p.id')
        ->orderBy('quantite_vendue', 'DESC')
        ->setMaxResults(20);

    return $qb->getQuery()->getResult();
    }

    function getProduitInfoByCategorie($nomCategorie) {
        $qb = $this->createQueryBuilder('p')
        ->select('p.id', 'p.nomProduit', 'p.prix', 'MIN(i.url) as image','pr.prix as prixpromo', 'cp.nom as nomCategoriePromo')
        ->leftJoin('p.laCategorie', 'c')
        ->leftJoin('p.lesImages', 'i')
        ->leftJoin('p.lesPromos', 'pr')
        ->leftJoin('pr.laCategoriePromo', 'cp')
        ->where('c.id = :categorie')
        ->andWhere('pr.dateDebut <= CURRENT_DATE() AND pr.dateFin >= CURRENT_DATE() OR pr.id IS NULL')
        ->orderBy('p.id')
        ->groupBy('p.id', 'p.nomProduit', 'p.prix', 'cp.nom') // Groupement par produit
        ->setParameter('categorie', $nomCategorie);
    
    $query = $qb->getQuery();
    $result = $query->getResult();
    
    return $result;

    }

//    /**
//     * @return Produits[] Returns an array of Produits objects
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

//    public function findOneBySomeField($value): ?Produits
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
