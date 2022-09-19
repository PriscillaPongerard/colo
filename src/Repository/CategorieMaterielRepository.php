<?php

namespace App\Repository;
use App\Entity\CategorieMateriel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Data\SearchData;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Proxies\__CG__\App\Entity\CategorieMateriel as EntityCategorieMateriel;

/**
 * @method CategorieMateriel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieMateriel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieMateriel[]    findAll()
 * @method CategorieMateriel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieMaterielRepository extends ServiceEntityRepository
{

    // /**
    //  * @var PaginatorInterface
    //  */
    // private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, CategorieMateriel::class);
        $this->paginator = $paginator;
    }
}
    // /**
    //  * @return CategorieMateriel[] Returns an array of CategorieMateriel objects
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
    public function findOneBySomeField($value): ?CategorieMateriel
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

