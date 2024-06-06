<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    // Afficher les stagiaires non inscrits dans une session
    public function findNotRegistered($session_id)
    {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // Sélectionner tous les stagiaires d'une session dont l'ID est passé en paramètre
        $qb->select('s')
            ->from('App\Entity\Student', 's')
            ->leftJoin('s.sessions', 'se')
            ->where('se.id = :id');

        $sub = $em->createQueryBuilder();
        // Sélectionner tous les stagiaires qui ne SONT PAS (NOT IN) dans le résultat précédent
        // On obtient donc les stagiaires non inscrits pour une session définie
        $sub->select('st')
            ->from('App\Entity\Student', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            // Requête paramétrée
            ->setParameter('id', $session_id)
            // Trier la liste des stagiaires sur le nom de famille
            ->orderBy('st.surname');
        
        // Renvoyer le résultat
        $query = $sub->getQuery();
        return $query->getResult();
    }

    // Afficher les modules non programmés dans une session
    public function findUnscheduledModules($session_id)
    {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // Sélectionner tous les modules d'une session dont l'ID est passé en paramètre
        $qb->select('m.id')
            ->from('App\Entity\Program', 'p')
            ->leftJoin('p.module', 'm')
            ->leftJoin('p.session', 's')
            ->where('s.id = :id');

        $sub = $em->createQueryBuilder();
        // Sélectionner tous les modules qui ne SONT PAS (NOT IN) dans le résultat précédent
        // On obtient donc les modules non programmés pour une session définie
        $sub->select('mo')
            ->from('App\Entity\Module', 'mo')
            ->where($sub->expr()->notIn('mo.id', $qb->getDQL()))
            // Requête paramétrée
            ->setParameter('id', $session_id)
            // Trier la liste des modules sur le titre
            ->orderBy('mo.title');
        
        // Renvoyer le résultat
        $query = $sub->getQuery();
        return $query->getResult();
    }

    //    /**
    //     * @return Session[] Returns an array of Session objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Session
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
