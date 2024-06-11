<?php

namespace App\Repository;

use App\Entity\Module;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Module>
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Module::class);
    }

    // Paginer les modules
    public function paginateModules(int $page): PaginationInterface
    {
        // Utilise le composant KnpPaginatorBundle pour paginer les résultats
        return $this->paginator->paginate(
            $this->createQueryBuilder('r'), // Requête pour récupérer tous les modules
            $page, // Numéro de la page à afficher
            6 // Nombre d'éléments par page
        );

        // Méthode alternative sans l'utilisation de KnpPaginatorBundle
        // return new Paginator(
        //     $this
        //         ->createQueryBuilder('r')
        //         ->setFirstResult(($page - 1) * $limit)
        //         ->setMaxResults($limit)
        //         ->getQuery()
        //         ->setHint(Paginator::HINT_ENABLE_DISTINCT, false),
        //         false
        // );
    }

    // Rechercher les modules par mot-clé
    public function findByWord($key)
    {
        // Obtient le gestionnaire d'entité
        $em = $this->getEntityManager();

        // Crée un constructeur de requête
        $sub = $em->createQueryBuilder();

        // Initialise la variable pour la requête
        $qb = $sub;

        // Construit la requête pour sélectionner les modules par titre similaire au mot-clé
        $qb->select('m')
            ->from('App\Entity\Module', 'm')
            ->where('m.title LIKE :key')
            ->setParameter('key', '%' . $key . '%');

        // Exécute la requête et retourne les résultats
        $query = $sub->getQuery();
        return $query->getResult();
    }

    //    /**
    //     * @return Module[] Returns an array of Module objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Module
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
