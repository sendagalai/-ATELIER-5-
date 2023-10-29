<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function searchBookByRef($ref){
        return $this->createQueryBuilder('b')
                ->where('b.ref LIKE :ref')
                ->setParameter('ref',$ref)
                ->getQuery()
                ->getResult();
            
    }
    public function ListByAuthors()
    {  $req=$this->createQueryBuilder('b')
                ->leftJoin('b.Author','a')
                 ->addorderBy('a.username','DESC')
                 ->getQuery()
                 ->getResult();
                 return $req;

 }
 public function findPublishedBefore2023()
 { $req=$this->createQueryBuilder('b')
               ->where('b.datePublication<:date')
               ->setParameter('date', new \DateTime('2023-01-01'))
               ->leftJoin('b.Author','a')
               ->groupBy('a.username')
               ->having('COUNT(b)>10')
               ->getQuery()
                 ->getResult();
                 return $req;


 }
 //DQL
 public function countRomance()
 {  $em=$this->getEntityManager();
    $query=$em->createQuery('SELECT COUNT(b) FROM App\Entity\Book b WHERE b.category =:category')->setParameter('category','Romance');
 return $query->getResult();
 }
 public function findBooksPublishedBetweenDates(\DateTime $startDate, \DateTime $endDate)
 {
     return $this->createQueryBuilder('b')
         ->where('b.datePublication>= :startDate')
         ->andWhere('b.datePublication <= :endDate')
         ->setParameter('startDate', $startDate)
         ->setParameter('endDate', $endDate)
         ->getQuery()
         ->getResult();
 }
 public function searchBookByRef2($ref)
 {
     return $this->createQueryBuilder('b')
         ->where('b.ref = :ref')
         ->setParameter('ref', $ref)
         ->getQuery()
         ->getResult();
 }
}
