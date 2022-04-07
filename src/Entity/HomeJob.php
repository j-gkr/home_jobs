<?php

namespace App\Entity;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HomeJobRepository")
 * @ORM\Table(name="hj_home_jobs")
 * @Gedmo\Loggable()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class HomeJob
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $deadline;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="homeJobs")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id", name="group_id")
     */
    private $group;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="homeJobs")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id", name="editor_id")
     */
    private $editor;

    /**
     * @var ScheduledHomeJob
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ScheduledHomeJob", inversedBy="homeJobs")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id", name="scheduled_home_job_id")
     */
    private $scheduledHomeJob;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned()
     */
    private $executionDate;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTime|null
     */
    public function getDeadline(): ?DateTime
    {
        return $this->deadline;
    }

    /**
     * @param DateTimeInterface $deadline
     * @return HomeJob
     */
    public function setDeadline(DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group): void
    {
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return User
     */
    public function getEditor(): User
    {
        return $this->editor;
    }

    /**
     * @param User $editor
     */
    public function setEditor(User $editor): void
    {
        $this->editor = $editor;
    }

    /**
     * @return ScheduledHomeJob
     */
    public function getScheduledHomeJob(): ScheduledHomeJob
    {
        return $this->scheduledHomeJob;
    }

    /**
     * @param ScheduledHomeJob $scheduledHomeJob
     * @param bool $stop
     */
    public function setScheduledHomeJob(ScheduledHomeJob $scheduledHomeJob, bool $stop = false): void
    {
        if (!$stop) {
            if ($scheduledHomeJob !== null) {
                $scheduledHomeJob->addHomeJob($this, true);
            } else {
                $this->scheduledHomeJob->removeHomeJob($this, true);
            }
        }

        $this->scheduledHomeJob = $scheduledHomeJob;
    }

    /**
     * @return DateTime|null
     */
    public function getExecutionDate(): ?DateTime
    {
        return $this->executionDate;
    }

    /**
     * @param DateTimeInterface|null $executionDate
     */
    public function setExecutionDate(?DateTimeInterface $executionDate): void
    {
        $this->executionDate = $executionDate;
    }

    /**
     * @return string
     */
    public function getBadgeClass(): string
    {
        try {
            $now = new DateTime();
            $dateInterval = $now->diff($this->getDeadline());

            switch($dateInterval->d) {
                case 0:
                    return 'badge-danger';
                case 1:
                    return 'badge-warning';
                default:
                    return 'badge-success';
            }

        } catch (Exception $exception) {
            return '';
        }
    }

    /**
     * @return DateInterval|null
     */
    public function untilDeadline(): ?DateInterval
    {
        try {
            $now = new DateTime();
            return $now->diff($this->getDeadline());
        } catch (Exception $exception) {
            return null;
        }
    }
}
