<?php

namespace App\Repository;

use App\Entity\CategoriePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoriePost|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriePost|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriePost[]    findAll()
 * @method CategoriePost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriePostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriePost::class);
    }

    // /**
    //  * @return CategoriePost[] Returns an array of CategoriePost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoriePost
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
