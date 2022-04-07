<?php

namespace App\Repository;

use App\Entity\HomeJob;
use App\Entity\ScheduledHomeJob;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method HomeJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method HomeJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method HomeJob[]    findAll()
 * @method HomeJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HomeJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomeJob::class);
    }

    /**
     * @return array
     */
    public function loadOpenHomeJobIds()
    {
        return $this->createQueryBuilder('hj')
            ->select('scheduledHomeJob.id')
            ->leftJoin('hj.scheduledHomeJob', 'scheduledHomeJob')
            ->where('hj.executionDate IS NULL')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    /**
     * @param ScheduledHomeJob $scheduledHomeJob
     * @return HomeJob|null
     */
    public function loadLastExecution(ScheduledHomeJob $scheduledHomeJob)
    {
        $result = $this->createQueryBuilder('hj')
            ->select('hj')
            ->leftJoin('hj.scheduledHomeJob', 'shj')
            ->where('shj.id = :shj')
            ->setParameter('shj', $scheduledHomeJob->getId())
            ->setMaxResults(1)
            ->addOrderBy('hj.executionDate', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        if (!empty($result)) {
            return $result[0];
        } else {
            return null;
        }
    }

    /**
     * @param User $user
     * @return Collection|HomeJob[]|null
     */
    public function loadLastHomeJobActivities(User $user)
    {
        $dql = 'SELECT hj.name, hj.executionDate, g.name as group_name, l.loggedAt, u.firstName
                , u.id as user_id, hj.id as hj_id, l.action, l.objectClass 
                FROM App\Entity\HomeJob hj
                LEFT JOIN Gedmo\Loggable\Entity\LogEntry l WITH l.objectId = hj.id AND l.objectClass = :objectClass
                LEFT JOIN App\Entity\Group g WITH g.id = hj.group
                LEFT JOIN App\Entity\User u WITH u.username = l.username
                WHERE l.username = :username
                ORDER BY l.loggedAt DESC'
        ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('objectClass', HomeJob::class);
        $query->setParameter('username', $user->getUsername());
        $query->setMaxResults(10);

        return $query->getArrayResult();
    }

    /**
     * @param User $user
     * @return Collection|HomeJob[]|null
     */
    public function loadOpenJobsByUser(User $user)
    {
        return $this->createQueryBuilder('hj')
            ->select('hj')
            ->where('hj.editor = :user')
            ->setParameter('user', $user)
            ->andWhere('hj.executionDate IS NULL')
            ->andWhere('hj.deadline >= CURRENT_TIMESTAMP()')
            ->addOrderBy('hj.deadline', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $user
     * @return Collection|HomeJob[]|null
     */
    public function loadDueJobsByUser(User $user)
    {
        return $this->createQueryBuilder('hj')
            ->select('hj')
            ->where('hj.editor = :user')
            ->setParameter('user', $user)
            ->andWhere('hj.executionDate IS NULL')
            ->andWhere('hj.deadline <= CURRENT_TIMESTAMP()')
            ->addOrderBy('hj.deadline', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
