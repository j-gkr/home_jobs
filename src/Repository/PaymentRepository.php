<?php

namespace App\Repository;

use App\Entity\HomeJob;
use App\Entity\Payment;
use App\Entity\User;
use App\Entity\Wallet;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * @param Wallet $wallet
     * @param DateTime $from
     * @param DateTime $to
     * @return Payment[]
     */
    public function loadByPeriod(Wallet $wallet, DateTime $from, DateTime $to)
    {
        return $this->createQueryBuilder('p')
            ->select('p, pc, c')
            ->leftJoin('p.paymentCategory', 'pc')
            ->leftJoin('p.creator', 'c')
            ->where('p.paymentDate >= :from')
            ->setParameter('from', $from)
            ->andWhere('p.paymentDate <= :to')
            ->setParameter('to', $to)
            ->andWhere('p.wallet = :wallet')
            ->setParameter('wallet', $wallet)
            ->addOrderBy('p.paymentDate', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $user
     * @return Collection|HomeJob[]|null
     */
    public function loadLastPaymentActivities(User $user)
    {
        $dql = 'SELECT p.name, pc.name as category, g.name as group_name, l.loggedAt, u.firstName
                , u.id as user_id, p.id as p_id, l.action, l.objectClass, p.amount
                FROM App\Entity\Payment p
                LEFT JOIN App\Entity\PaymentCategory pc WITH p.paymentCategory = pc.id
                LEFT JOIN Gedmo\Loggable\Entity\LogEntry l WITH l.objectId = p.id AND l.objectClass = :objectClass
                LEFT JOIN App\Entity\Wallet w WITH w.id = p.wallet
                LEFT JOIN App\Entity\Group g WITH g.id = w.group
                LEFT JOIN App\Entity\User u WITH u.username = l.username
                WHERE l.username = :username
                ORDER BY l.loggedAt DESC'
        ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('objectClass', Payment::class);
        $query->setParameter('username', $user->getUsername());
        $query->setMaxResults(10);

        return $query->getArrayResult();
    }

    // /**
    //  * @return Payment[] Returns an array of Payment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Payment
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
