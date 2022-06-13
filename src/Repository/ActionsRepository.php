<?php

namespace App\Repository;

use App\Entity\Actions;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Actions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actions[]    findAll()
 * @method Actions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actions::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Actions $entity, bool $flush = true): void
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
    public function remove(Actions $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    /**
     * Récupère les utilisateurs avec dernier date login et logout 
     * @return Actions[]
     */
    public function findUsersConnecter($nbr): array
    {

       $query = $this->createQueryBuilder('a')
            ->select('a','u')
            ->Where('a.typeAction LIKE :val')
            ->setParameter('val', 'connecte') 
           ->join('a.utilisateur', 'u')
           // ->groupBy('a.typeAction','u.id')
            ->orderBy('a.DateAction', 'DESC')
           ->setMaxResults($nbr)
             ;
        return $query->getQuery()
                     ->getResult()
                    ;

    }
    /**
     * Récupère les utilisateurs avec dernier date login et logout 
     * @return Actions[]
     */
    public function findUsersDeconecter($nbr): array
    {

       $query = $this->createQueryBuilder('a')
            ->select('a','u')
            ->Where('a.typeAction LIKE :val')
            ->setParameter('val', 'déconnecté') 
            ->join('a.utilisateur', 'u')
            ->orderBy('a.DateAction', 'DESC')
            //->groupBy('a.typeAction','u.id')
            ->setMaxResults($nbr)
             ;
        return $query->getQuery()
                     ->getResult()
                    ;

    }

    // /**
    //  * @return Actions[] Returns an array of Actions objects
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
    public function findOneBySomeField($value): ?Actions
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
