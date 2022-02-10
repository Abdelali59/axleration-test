<?php

namespace App\Repository;

use App\Entity\Automobiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Automobiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Automobiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Automobiles[]    findAll()
 * @method Automobiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutomobilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Automobiles::class);
    }

    public function findAutosAPI()
    {
        return $this->createQueryBuilder('a')
            ->select('a.id')
            ->addSelect('a.name')
            ->orderBy('a.id')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findAutosAPIById($id)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->orderBy('a.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAutosWithCat()
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c')
            ->addSelect('c')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Automobiles[] Returns an array of Automobiles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Automobiles
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
