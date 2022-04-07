<?php


namespace App\Form\Request;


use App\Entity\ScheduledHomeJob;
use App\Entity\User;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Exception;

/**
 * Class HomeJobRequest
 *
 * @package App\Form\Request
 */
class ScheduledHomeJobRequest
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var DateInterval|null
     */
    private $period;

    /**
     * @var DateTimeInterface|null
     */
    private $startDate;

    /**
     * @var User|null
     */
    private $editor;

    /**
     * HomeJobRequest constructor.
     * @param ScheduledHomeJob $job
     * @throws Exception
     */
    public function __construct(ScheduledHomeJob $job)
    {
        $this->name = '';
        $this->description = '';
        $this->period = DateInterval::createFromDateString("2 weeks");
        $this->startDate = new DateTime();
        $this->startDate->setTime(0,0,0);

        if ($job !== null) {
            $this->extractEntity($job);
        }
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return DateInterval|null
     */
    public function getPeriod(): ?DateInterval
    {
        return $this->period;
    }

    /**
     * @param DateInterval|null $period
     */
    public function setPeriod(?DateInterval $period): void
    {
        $this->period = $period;
    }

    /**
     * @param ScheduledHomeJob $job
     *
     * @return void
     */
    public function extractEntity(ScheduledHomeJob $job): void
    {
        if ($job->getName() !== null) {
            $this->setName($job->getName());
        }

        if ($job->getDescription() !== null) {
            $this->setDescription($job->getDescription());
        }

        if ($job->getPeriod() !== null) {
            $this->setPeriod($job->getPeriod());
        }

        if ($job->getStartDate() !== null) {
            $this->setStartDate($job->getStartDate());
        }

        if ($job->getEditor() !== null) {
            $this->setEditor($job->getEditor());
        }
    }

    /**
     * @param ScheduledHomeJob $job
     * @return void
     */
    public function fillEntity(ScheduledHomeJob $job): void
    {
        $job->setPeriod($this->getPeriod());
        $job->setName($this->getName());
        $job->setDescription($this->getDescription());
        $job->setStartDate($this->getStartDate());
        $job->setEditor($this->getEditor());
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @param DateTimeInterface|null $startDate
     */
    public function setStartDate(?DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return User|null
     */
    public function getEditor(): ?User
    {
        return $this->editor;
    }

    /**
     * @param User|null $editor
     */
    public function setEditor(?User $editor): void
    {
        $this->editor = $editor;
    }
}