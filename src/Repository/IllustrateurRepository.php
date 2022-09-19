<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Illustrateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Illustrateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Illustrateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Illustrateur[]    findAll()
 * @method Illustrateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IllustrateurRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Illustrateur::class);
        $this->paginator = $paginator;
    }

    /**
     * Récupère la liste des illustrateur avec la recherche
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p');

        if (!empty($search->q)) {
            $query = $query
                ->orWhere('p.nomIllustrateur LIKE :q')
                ->setParameter('q', "%{$search->q}%");

            $query = $query
                ->orWhere('p.prenom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            6
        );
    }

    // /**
    //  * @return Illustrateur[] Returns an array of Illustrateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Illustrateur
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
