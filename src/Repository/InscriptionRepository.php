<?php

namespace App\Repository;

use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inscription>
 *
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }
    public function rechlnscriptionsEmploye($nom, $prenom): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.employe','e')
            ->andWhere('e.nom = :val1')
            ->andWhere('e. prenom = :val2')
            ->setParameter('val1', $nom)
            ->setParameter('val2', $prenom)
            ->getQuery()
            ->getResult()
            ;
    }
    public function rechInscriptionsProduit($produit): array
    {
        $result = $this->createQueryBuilder('i')
            ->join('i.formation', 'f')
            ->join('f.produit', 'p')
            ->andWhere('p.libelle = :produit')
            ->setParameter('produit', $produit)
            ->getQuery()
            ->getResult();

        dump($result); // Ajoutez cette ligne pour afficher les résultats dans la console

        return $result;
    }

//    /**
//     * @return Inscription[] Returns an array of Inscription objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Inscription
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
