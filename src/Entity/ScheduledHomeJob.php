<?php

namespace App\Entity;

use DateInterval;
use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Represents a scheduledHomeJob for a group.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ScheduledHomeJobRepository")
 * @ORM\Table(name="hj_scheduled_home_job")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class ScheduledHomeJob
{
    use TimestampableEntity;
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
    private $startDate;

    /**
     * @var DateInterval
     *
     * @ORM\Column(type="dateinterval")
     */
    private $period;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="scheduledHomeJobs")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id", name="group_id")
     */
    private $group;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="scheduledHomeJobs")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id", name="editor_id")
     */
    private $editor;

    /**
     * @var HomeJob|Collection|null
     *
     * @ORM\OneToMany(targetEntity="App\Entity\HomeJob", mappedBy="scheduledHomeJob", cascade={"all"})
     */
    private $homeJobs;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ScheduledHomeJob
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return ScheduledHomeJob
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return DateInterval|null
     */
    public function getPeriod(): ?DateInterval
    {
        return $this->period;
    }

    /**
     * @param DateInterval $period
     *
     * @return ScheduledHomeJob
     */
    public function setPeriod(DateInterval $period): self
    {
        $this->period = $period;

        return $this;
    }

    /**
     * @return Group|null
     */
    public function getGroup(): ?Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     * @param bool $stop
     */
    public function setGroup(Group $group, bool $stop = false): void
    {
        $this->group = $group;
    }

    /**
     * @return User
     */
    public function getEditor(): ?User
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
     * @return DateTime
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return Collection|HomeJob
     */
    public function getHomeJobs(): ?Collection
    {
        return $this->homeJobs;
    }

    /**
     * @param HomeJob $homeJobs
     */
    public function setHomeJobs(HomeJob $homeJobs): void
    {
        $this->homeJobs = $homeJobs;
    }

    /**
     * @param HomeJob $job
     * @param bool $stop
     */
    public function addHomeJob(HomeJob $job, bool $stop = false)
    {
        if (!$this->homeJobs->contains($job)) {
            $this->homeJobs->add($job);
        }

        if (!$stop) {
            $job->setScheduledHomeJob($this, true);
        }
    }

    /**
     * @param HomeJob $job
     * @param bool $stop
     */
    public function removeHomeJob(HomeJob $job, bool $stop = false)
    {
        if ($this->homeJobs->contains($job)) {
            $this->homeJobs->removeElement($job);
        }

        if (!$stop) {
            $job->setScheduledHomeJob(null, true);
        }
    }
}
