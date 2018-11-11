<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function deleteIfNotIn($array)
    {
        try {
            $this->_em->createQuery('
            DELETE
              App\Entity\Book b            
            WHERE
              b.name
            NOT IN
              (:filesArray) 
        ')->setParameter('filesArray', $array, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                ->getResult();
        } catch (\Exception $e) {
            echo 'Excpetion thrown in App\Repository\BookRepository. Message: ' . $e->getMessage();
        }
    }
}
