<?php


namespace App\Repository;


use App\Entity\Group;
use App\Entity\GroupUserConfiguration;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GroupUserConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupUserConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupUserConfiguration[]    findAll()
 * @method GroupUserConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupUserConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupUserConfiguration::class);
    }


    /**
     * @param User $user
     * @return GroupUserConfiguration[]|null
     */
    public function loadByUser(User $user): ?array
    {
        return $this->createQueryBuilder('guc')
            ->select('guc, u, g, w')
            ->leftJoin('guc.user', 'u')
            ->leftJoin('guc.group', 'g')
            ->leftJoin('g.wallets', 'w')
            ->where('u.id = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $user
     * @return array
     */
    public function loadGroupIdsByUser(User $user)
    {
        return $this->createQueryBuilder('guc')
            ->select('g.id')
            ->leftJoin('guc.user', 'u')
            ->leftJoin('guc.group', 'g')
            ->where('u.id = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getArrayResult()
        ;
    }
}