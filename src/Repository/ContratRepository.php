<?php

namespace App\Repository;

use App\Entity\Contrat;
use App\Data\SearchData;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Contrat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrat[]    findAll()
 * @method Contrat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrat::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Contrat $entity, bool $flush = true): void
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
    public function remove(Contrat $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Contrat[] Returns an array of Contrat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    *
    */
    // public function findByCentre()
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.codeCentre = :centre')
    //        // ->setParameter('val', $value)
    //         ->getQuery()
    //         //->getOneOrNullResult()
    //     ;
    // }
    

    /**
     * Récupère les contrats en lien avec une recherche
     * @return Contrat[]
     */
   public function findSearch($search): array
   {
      $query = $this
           ->createQueryBuilder('c')
           ->select('c')
          // ->andWhere('c.publier = true')
            ->orderBy('c.dateSauvegarde', 'DESC');

       if (!empty($search->ref)) {
           $query = $query
               ->andWhere('c.reference LIKE :ref')
               ->setParameter('ref', "%{$search->ref}%") ;
       }
       if (!empty($search->codeRecette)) {
           $query = $query
               ->andWhere('c.codeRecette LIKE :codeRecette')
               ->setParameter('codeRecette', "%{$search->codeRecette}%") ;
       }

         if (!empty($search->contrat)) {
           // if (!empty($search->contrat->getCodeCentre())) {
                dd();
           $query = $query
               ->andWhere('c.codeCentre like :contrat')
               ->setParameter('contrat', $search->contrat) ;
       }

       if (!empty($search->date)) {
           $query = $query
               ->andWhere('c.dateSauvegarde = :date')
               ->setParameter('date', $search->date)  ;
       }
       return $query->getQuery()
                    ->getResult()
                   ;

   }
   /**
     * Récupère les contrats en lien avec une recherche
     * @return Contrat[]
     */
    public function Statistique($stat): array
    {
       $query = $this
            ->createQueryBuilder('c')
            ->select('c')
         //   ->orderBy('c.dateSauvegarde', 'DESC')
         ;
 
        if ((!empty($stat->date_depart)) && (!empty($stat->date_final))) {
            $query = $query
                ->andWhere('c.dateSauvegarde BETWEEN :date_depart and :date_final')
                ->setParameter('date_depart', $stat->date_depart)
                ->setParameter('date_final', $stat->date_final)
                  ;
        }
        return $query->getQuery()
                     ->getResult()
                    ;
    }



}
