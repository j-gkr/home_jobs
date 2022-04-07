<?php

namespace App\Repository;

use App\Entity\ScheduledHomeJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ScheduledHomeJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduledHomeJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduledHomeJob[]    findAll()
 * @method ScheduledHomeJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduledHomeJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduledHomeJob::class);
    }

    /**
     * @param array $openHomeJobIds
     * @return ScheduledHomeJob[]|null
     */
    public function loadScheduledHomeJobsForGeneration(array $openHomeJobIds)
    {
        if (!empty($openHomeJobIds)) {
            return $this->createQueryBuilder('scheduledHomeJob')
                ->select('scheduledHomeJob')
                ->where('scheduledHomeJob.id NOT IN (:ids)')
                ->andWhere('scheduledHomeJob.startDate <= CURRENT_TIMESTAMP()')
                ->setParameter('ids', $openHomeJobIds)
                ->getQuery()
                ->getResult()
                ;
        } else {
            return $this->createQueryBuilder('scheduledHomeJob')
                ->select('scheduledHomeJob')
                ->getQuery()
                ->getResult()
                ;
        }


    }

    // /**
    //  * @return ScheduledHomeJob[] Returns an array of ScheduledHomeJob objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ScheduledHomeJob
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
