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

    // je cree ma function quelle est different de find et je la associe avec $search
    public function searchByTerm($search)
    {
        //je cree mon querybuilder pour cree ma roquete
        $qb = $this->createQueryBuilder('class');
        // je cree ma roquete select ( presque pareil comme sql)
        $query = $qb-> select('class')
            ->where('class.name LIKE :search')
            ->orWhere('class.description LIKE :search')
            // setparameter c'est la securite pour input recherche ( fait jamais confiance a utilisateur)
            // dans la where je mette :search que j'appelle a set parameter 'search' qui contient enfin ma variable $search, du coup avec ca je le mettre pas direct dans ma roquete
            ->setParameter('search', '%'.$search.'%')
            // getQuery me rasamble tout la roquete
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
