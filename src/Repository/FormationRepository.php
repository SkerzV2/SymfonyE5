<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 *
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function hasInscriptions($formationId)
    {
        $qb = $this->createQueryBuilder('f')
            ->select('CASE WHEN COUNT(i) > 0 THEN 1 ELSE 0 END AS inscription_exist')
            ->leftJoin('f.inscription', 'i')
            ->where('f.id = :formationId')
            ->setParameter('formationId', $formationId);

        return $qb->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Formation[] Returns an array of Formation objects
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

//    public function findOneBySomeField($value): ?Formation
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
