<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /**
     * @param string $name
     *
     * @return Group|null
     * @throws NonUniqueResultException
     */
    public function loadGroupByName(string $name): ?Group
    {
        return $this->createQueryBuilder('g')
            ->select('g')
            ->where('g.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string $name
     * @param int $id
     *
     * @return Group|null
     *
     * @throws NonUniqueResultException
     */
    public function loadGroupByNameAndNotId(string $name, int $id): ?Group
    {
        return $this->createQueryBuilder('g')
            ->select('g')
            ->where('g.name = :name')
            ->setParameter('name', $name)
            ->andWhere('g.id != :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param array $groupIds
     * @return Group[]|null
     */
    public function loadOpenHomeJobsByGroups(array $groupIds)
    {
        return $this->createQueryBuilder('g')
            ->select('g, hj')
            ->leftJoin('g.homeJobs', 'hj')
            ->where('g.id IN (:groups)')
            ->setParameter('groups', $groupIds)
            ->andWhere('hj.executionDate IS NULL')
            ->andWhere('hj.deadline >= CURRENT_TIMESTAMP()')
            ->addOrderBy('hj.deadline')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param array $groupIds
     * @return Group[]|null
     */
    public function loadDueHomeJobsByGroups(array $groupIds)
    {
        return $this->createQueryBuilder('g')
            ->select('g, hj')
            ->leftJoin('g.homeJobs', 'hj')
            ->where('g.id IN (:groups)')
            ->setParameter('groups', $groupIds)
            ->andWhere('hj.executionDate IS NULL')
            ->andWhere('hj.deadline < CURRENT_TIMESTAMP()')
            ->addOrderBy('hj.deadline')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param array $groupIds
     * @return Group[]|null
     */
    public function loadLastExecutedHomeJobsByGroups(array $groupIds) {

        return $this->createQueryBuilder('g')
            ->select('g, hj')
            ->leftJoin('g.homeJobs', 'hj')
            ->where('g.id IN (:groups)')
            ->setParameter('groups', $groupIds)
            ->andWhere('hj.executionDate IS NOT NULL')
            ->orderBy('hj.executionDate', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $groups
     * @return Group[]|null
     */
    public function loadUserByGroups($groups)
    {
        return $this->createQueryBuilder('g')
            ->select('g, guc, user')
            ->leftJoin('g.groupConfigurations', 'guc')
            ->leftJoin('guc.user', 'user')
            ->where('g.id IN (:groups)')
            ->setParameter('groups', $groups)
            ->orderBy('user.lastName')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Group[] Returns an array of Group objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Group
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
