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



    public function ChercherBookAbecREF($ref){

        return $this->createQueryBuilder('b')
            ->where('b.ref like :ref')
            ->setParameter('ref',$ref)
            ->getQuery()
            ->getResult();
            
    }


    public function TrierParAuteur(){

        return $this->createQueryBuilder('b')
            ->orderBy('b.author','ASC')
            ->getQuery()
            ->getResult();

    }


    public function LivresPublieAvant2023(){

        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->where('b.publicationdate < :date')
            ->andWhere('a.nbrbooks > 35')
            ->setParameter('date','2023-01-01')
            ->getQuery()
            ->getResult();

    }

    
        public function updateCategory($category, $nameW)
        {
            return $this->createQueryBuilder('b')
                ->update()
                ->set('b.category', ':category')
                ->where('b.author IN (SELECT a.id FROM App\Entity\Author a WHERE a.username LIKE :nameW)')
                ->setParameter('category', $category)
                ->setParameter('nameW', $nameW)
                ->getQuery()
                ->execute();
        }


        public function NBROfSIFIBooks()
        {
            $em = $this->getEntityManager();
            $query = $em->createQuery('SELECT COUNT(b.ref) FROM App\Entity\Book b WHERE b.category = :category')
                ->setParameter('category', 'Science-Fiction');
            return $query->getSingleScalarResult();
        }


        public function AfficherLivresPublieEntreDeuxDate()
        {
            $em = $this->getEntityManager();
            $query = $em->createQuery('SELECT b FROM App\Entity\Book b WHERE b.publicationdate BETWEEN :a AND :b')
                ->setParameter('a', '2014-01-01')
                ->setParameter('b', '2018-12-31');
            return $query->getResult();
        }
    
    

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
