<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Livre::class);
        $this->paginator = $paginator;
    }

    //Fonction pour recuperer la liste de livre avec les jointures 
    /**
     * @param [type] $search
     * @return PaginationInterface
     */
    public function findSearch($search): PaginationInterface
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQueryBuilder('p')
            ->select('p, i, c')
            ->from('App\Entity\Livre', 'p')
            ->join('p.illustrateur', 'i')
            ->join('p.categorieMateriel', 'c');
        if (!empty($search->q)) {
            $query->orWhere('p.titreLivre LIKE :q')
                ->setParameter('q', "%{$search->q}%");
            $query->orWhere('i.nomIllustrateur LIKE :q')
                ->setParameter('q', "%{$search->q}%");
            $query->orWhere('i.prenom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
            $query->orWhere('c.nomCategorie LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }
        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            6
        );
    }
}
