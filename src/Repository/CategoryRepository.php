<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findByWord($key)
    {
        // Obtient l'EntityManager pour interagir avec la base de données.
        $em = $this->getEntityManager();
    
        // Crée un nouveau QueryBuilder.
        $sub = $em->createQueryBuilder();
    
        // Référence au QueryBuilder pour simplifier le code.
        $qb = $sub;
    
        // Construit la requête pour sélectionner les entités 'Category' dont le nom contient le mot clé.
        $qb->select('c') // Sélectionne l'entité 'Category' en tant que 'c'.
            ->from('App\Entity\Category', 'c') // Définit la source des données comme étant l'entité 'Category'.
            ->where('c.name LIKE :key') // Ajoute une condition WHERE pour filtrer les catégories dont le nom contient le mot clé.
            ->setParameter('key', '%' . $key . '%'); // Définit le paramètre 'key' avec des jokers pour une recherche partielle.
    
        // Crée et exécute la requête.
        $query = $sub->getQuery();
    
        // Retourne les résultats de la requête.
        return $query->getResult();
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
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

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
