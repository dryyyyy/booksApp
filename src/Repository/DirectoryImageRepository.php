<?php

namespace App\Repository;

use App\Entity\DirectoryImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DirectoryImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DirectoryImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DirectoryImage[]    findAll()
 * @method DirectoryImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectoryImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DirectoryImage::class);
    }

    // /**
    //  * @return DirectoryImage[] Returns an array of DirectoryImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DirectoryImage
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
