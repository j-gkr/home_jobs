<?php


namespace App\Manager;


use App\Entity\HomeJob;
use App\Entity\Payment;
use App\Entity\User;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ActivityManager
 *
 * @package App\Manager
 */
class ActivityManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ActivityManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @return HomeJob[]|Collection|null
     */
    public function loadLastActivitiesByUser(User $user)
    {
        $this->entityManager->clear();
        $this->entityManager->getFilters()->disable('softdeleteable');
        $homeJobActivities = $this->entityManager->getRepository(HomeJob::class)->loadLastHomeJobActivities($user);
        $paymentActivities = $this->entityManager->getRepository(Payment::class)->loadLastPaymentActivities($user);
        $activities = array_merge($homeJobActivities, $paymentActivities);

        // sort merged arrays by loggedAt Timestamps
        usort($activities, [$this, 'sortByLoggedAt']);

        $this->entityManager->getFilters()->enable('softdeleteable');
        return $activities;
    }

    /**
     * @param $a1
     * @param $a2
     * @return int
     */
    private function sortByLoggedAt($a1, $a2): int
    {
        /** @var DateTime $v1 */
        $v1 = $a1['loggedAt'];
        /** @var DateTime $v2 */
        $v2 = $a2['loggedAt'];

        return $v2->getTimestamp() - $v1->getTimestamp(); // $v2 - $v1 to reverse direction
    }
}