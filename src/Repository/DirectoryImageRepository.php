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
}
