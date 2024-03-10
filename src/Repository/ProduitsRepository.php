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

    public function findLeastSoldLastWeek()
{
    // Date d'il y a une semaine
    $dateIlYaUneSemaine = new \DateTime('-1 week');

    // Création du Query Builder
    $qb = $this->createQueryBuilder('p')
    ->select('p.id, p.nomProduit, p.prix, SUM(c.quantite) as quantite_vendue, MIN(i.url) as imageUrl')
    ->join('App\Entity\Commander', 'c', Join::WITH, 'p.id = c.leProduit')
    ->join('App\Entity\Commandes', 'co', Join::WITH, 'c.laCommande = co.id')
    ->leftJoin('p.lesImages', 'i')
    ->where('co.dateCommande > :dateIlYaUneSemaine')
    ->setParameter('dateIlYaUneSemaine', $dateIlYaUneSemaine)
    ->groupBy('p.id')
    ->orderBy('quantite_vendue', 'ASC') // Tri par quantité vendue en ordre croissant
    ->setMaxResults(1); // Limite les résultats au produit le moins vendu

    $query = $qb->getQuery();
        $result = $query->getOneOrNullResult(); // Utilisez getOneOrNullResult() pour obtenir un seul résultat ou null si aucun n'est trouvé
    
        return $result;
}


    function getProduitInfoByCategorie($nomCategorie) {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id', 'p.nomProduit','p.descriptioncourte', 'p.prix', 'MIN(i.url) as image', 'pr.prix as prixpromo', 'cp.nom as nomCategoriePromo')
            ->leftJoin('p.laCategorie', 'c')
            ->leftJoin('p.lesImages', 'i')
            ->leftJoin('p.lesPromos', 'pr', 'WITH', 'pr.dateDebut <= CURRENT_DATE() AND pr.dateFin >= CURRENT_DATE() OR pr.id IS NULL')
            ->leftJoin('pr.laCategoriePromo', 'cp')
            ->where('c.id = :categorie')
            ->orderBy('p.id')
            ->groupBy('p.id', 'p.nomProduit', 'p.prix', 'cp.nom')
            ->setParameter('categorie', $nomCategorie);
    
        $query = $qb->getQuery();
        $result = $query->getResult();
    
        return $result;
    }

    
    function getProduitPromoduJour($nomCategorie) {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id', 'p.nomProduit', 'pr.prix as prixpromo', 'pr.dateFin as fin', 'cp.nom as nomCategoriePromo', 'MIN(i.url) as image')
            ->leftJoin('p.lesPromos', 'pr', 'WITH', 'pr.dateDebut <= CURRENT_TIMESTAMP() AND pr.dateFin >= CURRENT_TIMESTAMP() OR pr.id IS NULL')
            ->leftJoin('pr.laCategoriePromo', 'cp')
            ->leftJoin('p.lesImages', 'i')
            ->where('cp.nom = :nomcategorie')
            ->orderBy('p.id')
            ->groupBy('p.id', 'p.nomProduit')
            ->setParameter('nomcategorie', $nomCategorie)
            ->setMaxResults(1); // Limite les résultats à un seul produit
    
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult(); // Utilisez getOneOrNullResult() pour obtenir un seul résultat ou null si aucun n'est trouvé
    
        return $result;
    }
    
    

    function getProduitPromo($nomCategorie) {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id', 'p.nomProduit', 'pr.prix as prixpromo', 'pr.dateFin as fin', 'cp.nom as nomCategoriePromo')
            ->leftJoin('p.lesPromos', 'pr', 'WITH', 'pr.dateDebut <= CURRENT_TIMESTAMP() AND pr.dateFin >= CURRENT_TIMESTAMP() OR pr.id IS NULL')
            ->leftJoin('pr.laCategoriePromo', 'cp')
            ->where('p.id = :nomcategorie')
            ->orderBy('p.id')
            ->groupBy('p.id', 'p.nomProduit')
            ->setParameter('nomcategorie', $nomCategorie);
    
        $query = $qb->getQuery();
        $result = $query->getResult();
    
        return $result;
    }
    
    function getProduitInfoByMotCle($motCle) {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id', 'p.nomProduit', 'p.prix', 'MIN(i.url) as image', 'pr.prix as prixpromo', 'cp.nom as nomCategoriePromo')
            ->leftJoin('p.laCategorie', 'c')
            ->leftJoin('p.lesImages', 'i')
            ->leftJoin('p.lesPromos', 'pr', 'WITH', 'pr.dateDebut <= CURRENT_DATE() AND pr.dateFin >= CURRENT_DATE()')
            ->leftJoin('pr.laCategoriePromo', 'cp')
            ->where('p.nomProduit LIKE :motCle')
            ->orderBy('p.id')
            ->groupBy('p.id', 'p.nomProduit', 'p.prix', 'cp.nom')
            ->setParameter('motCle', '%'.$motCle.'%');
    
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
