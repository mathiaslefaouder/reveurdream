<?php

namespace App\Repository;

use App\Entity\Dream;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dream|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dream|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dream[]    findAll()
 * @method Dream[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DreamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dream::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Dream $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Dream $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Dream[] Returns an array of Dream objects
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


    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findPreLast(User $user, Dream $dream): ?Dream
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.id', 'DESC')
            ->where('d.author = :user')
            ->andWhere('d.id != :dream')
            ->setParameter('user', $user)
            ->setParameter('dream', $dream)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }

    public function findAllNotDraft(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.id', 'DESC')
            ->where('d.isDraft = false')
            ->getQuery()
            ->getResult()
            ;
    }
}
