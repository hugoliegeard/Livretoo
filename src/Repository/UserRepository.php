<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByStatus(string $role)
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', "%$role%")
            ->getQuery()
            ->getResult()
        ;
    }

    /*public function findAuthorArticlesByStatus(int $idAuthor, string $status)
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :author_id')
            ->setParameter('author_id', $idAuthor)
            ->andWhere('a.status LIKE :status')
            ->setParameter('status', "%$status%")
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }*/
}
