<?php

namespace App\Repository;

use App\Entity\Carnet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Carnet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carnet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carnet[]    findAll()
 * @method Carnet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarnetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carnet::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Carnet $entity, bool $flush = true): void
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
    public function remove(Carnet $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Carnet[] Returns an array of Carnet objects
     */
   
    public function findcarnetsByName(string $query)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('c.nom', ':query'),
                        $qb->expr()->like('c.categorie', ':query'),
                    ),
                    // $qb->expr()->isNotNull('c.created_at')
                )
            )
            ->setParameter('query', '%' . $query . '%')
        ;
        return $qb
            ->getQuery()
            ->getResult();
    }
   

    /*
    public function findOneBySomeField($value): ?Carnet
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
