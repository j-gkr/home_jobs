<?php


namespace App\Manager;


use App\Entity\HomeJob;
use App\Entity\ScheduledHomeJob;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class ScheduledHomeJobManager
 *
 * @package App\Manager
 */
class ScheduledHomeJobManager
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ScheduledHomeJobManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return bool
     */
    public function generateHomeJobs(): bool
    {
        try {
            $openHomeJobIds = $this->entityManager->getRepository(HomeJob::class)->loadOpenHomeJobIds();
            $allScheduledHomeJobs = $this->entityManager->getRepository(ScheduledHomeJob::class)->loadScheduledHomeJobsForGeneration($openHomeJobIds);

            foreach ($allScheduledHomeJobs as $scheduledHomeJob) {

                $lastExecutedJob = $this->entityManager->getRepository(HomeJob::class)->loadLastExecution($scheduledHomeJob);

                if ($lastExecutedJob !== null) {
                    $lastExecution = $lastExecutedJob->getExecutionDate();
                    $lastExecution->setTime(0,0,0);
                    $calc = DateTimeImmutable::createFromMutable($lastExecution);
                } else {
                    $startDate = $scheduledHomeJob->getStartDate();
                    $startDate->setTime(0,0,0);
                    $calc = DateTimeImmutable::createFromMutable($scheduledHomeJob->getStartDate());
                }

                $deadline = $calc->add($scheduledHomeJob->getPeriod());
                $newHomeJob = new HomeJob();
                $newHomeJob->setName($scheduledHomeJob->getName());
                $newHomeJob->setDescription($scheduledHomeJob->getDescription());
                $newHomeJob->setScheduledHomeJob($scheduledHomeJob, true);
                $newHomeJob->setGroup($scheduledHomeJob->getGroup());
                $newHomeJob->setDeadline($deadline);
                $newHomeJob->setEditor($scheduledHomeJob->getEditor());
                $this->entityManager->persist($newHomeJob);
            }

            $this->entityManager->flush();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param ScheduledHomeJob $scheduledHomeJob
     *
     * @return bool
     */
    public function delete(ScheduledHomeJob $scheduledHomeJob): bool
    {
        try {
            foreach ($scheduledHomeJob->getHomeJobs() as $homeJob) {
                $this->entityManager->remove($homeJob);
            }

            $this->entityManager->remove($scheduledHomeJob);
            $this->entityManager->flush();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}