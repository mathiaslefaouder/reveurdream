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

    final public function setAllDraftExcept(User $user, Dream $dream): void
    {
        $this->createQueryBuilder('d')
            ->set('d.isDraft', true)
            ->where('d.author = :user')
            ->andWhere('d.id != :dream')
            ->setParameter('user', $user)
            ->setParameter('dream', $dream)
            ->getQuery()
            ->execute()
            ;
    }


    public function findAllNotDraft(string $lang): ?array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.id', 'DESC')
            ->where('d.isDraft = false')
            ->andWhere('d.lang = :lang')
            ->setParameter('lang', $lang)
            ->getQuery()
            ->getResult()
            ;
    }

    public function dataForMap(string $lang)
    {
        return $this->createQueryBuilder('d')
            ->select('d.id', 'd.title', 'd.description', 'd.gps', 'd.createdAt', 'c.name AS category', 'c.ico as cat_ico', 't.name as theme', 't.ico as theme_ico', 't.svgPin as theme_pin_ico', 't.short as theme_short')
            ->innerJoin('d.category', 'c')
            ->innerJoin('d.theme', 't')
        ->orderBy('d.gps', 'ASC')
        ->where('d.isDraft = false')
        ->andWhere('d.lang = :lang')
        ->setParameter('lang', $lang)
        ->getQuery()
        ->getResult()
        ;
    }
}
