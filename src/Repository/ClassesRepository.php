<?php

namespace App\Repository;

use App\Entity\Classes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Classes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classes[]    findAll()
 * @method Classes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classes::class);
    }

    // je crée ma fonction qui est différente de find et je l'associe avec $search
    public function searchByTerm($search)
    {
        //je crée mon querybuilder pour créer ma requête
        $qb = $this->createQueryBuilder('class');
        // je crée ma requête select (presque de la même manière que sql)
        $query = $qb-> select('class')
            ->where('class.name LIKE :search')
            ->orWhere('class.description LIKE :search')
            // setparameter est une sécurité pour input recherche (ne jamais faire confiance a un utilisateur)
            // dans la where je mets like :search que j'appelle via setparameter 'search' qui contient enfin ma variable $search, du coup avec ca je ne le mets pas directement dans ma requête
            ->setParameter('search', '%'.$search.'%')
            // getQuery me rassemble toute la requête
            ->getQuery();


        return $query->getResult();

    }



    // /**
    //  * @return Classes[] Returns an array of Classes objects
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
    public function findOneBySomeField($value): ?Classes
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
