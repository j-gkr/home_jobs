<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Class represents a system group.
 *
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\Table(name="hj_group")
 * @JMS\ExclusionPolicy("all")
 */
class Group
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    private $name;

    /**
     * @var float|null
     *
     * @ORM\Column(type="float", nullable=true)
     * @JMS\Expose()
     */
    private $budget;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Expose()
     */
    private $street;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Expose()
     */
    private $housenumber;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Expose()
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Expose()
     */
    private $zip;

    /**
     * @var Collection|ScheduledHomeJob[]|null
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ScheduledHomeJob", mappedBy="group", cascade={"all"})
     */
    private $scheduledHomeJobs;

    /**
     * @var Collection|HomeJob[]|null
     *
     * @ORM\OneToMany(targetEntity="App\Entity\HomeJob", mappedBy="group", cascade={"all"})
     */
    private $homeJobs;

    /**
     * @var GroupUserConfiguration[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\GroupUserConfiguration", mappedBy="group")
     */
    private $groupConfigurations;

    /**
     * @var Collection|Wallet[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Wallet", mappedBy="group")
     * @JMS\Expose()
     */
    private $wallets;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->name = '';
        $this->groupConfigurations = new ArrayCollection();
        $this->wallets = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return Group
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     * @return Group
     */
    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHousenumber(): ?string
    {
        return $this->housenumber;
    }

    /**
     * @param string|null $housenumber
     * @return Group
     */
    public function setHousenumber(?string $housenumber): self
    {
        $this->housenumber = $housenumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return Group
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     * @return Group
     */
    public function setZip(?string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return ScheduledHomeJob[]|null
     */
    public function getScheduledHomeJobs(): ?Collection
    {
        return $this->scheduledHomeJobs;
    }

    /**
     * @param Collection|ScheduledHomeJob[]|null $scheduledHomeJobs
     */
    public function setScheduledHomeJobs(?Collection $scheduledHomeJobs): void
    {
        $this->scheduledHomeJobs = $scheduledHomeJobs;
    }

    /**
     * @return HomeJob[]|Collection|null
     */
    public function getHomeJobs()
    {
        return $this->homeJobs;
    }

    /**
     * @param HomeJob[]|Collection|null $homeJobs
     */
    public function setHomeJobs($homeJobs): void
    {
        $this->homeJobs = $homeJobs;
    }

    /**
     * @return GroupUserConfiguration[]|Collection
     */
    public function getGroupConfigurations(): Collection
    {
        return $this->groupConfigurations;
    }

    /**
     * @param GroupUserConfiguration $configuration
     * @param bool $stop
     */
    public function addGroupUserConfiguration(GroupUserConfiguration $configuration, bool $stop = false): void
    {
        if (!$this->groupConfigurations->contains($configuration)) {
            $this->groupConfigurations->add($configuration);
        }

        if (!$stop) {
            $configuration->setGroup($this, true);
        }
    }

    /**
     * @param GroupUserConfiguration $configuration
     * @param bool $stop
     */
    public function removeGroupUserConfiguration(GroupUserConfiguration $configuration, bool $stop = false): void
    {
        if ($this->groupConfigurations->contains($configuration)) {
            $this->groupConfigurations->removeElement($configuration);
        }

        if (!$stop) {
            $configuration->setGroup(null, true);
        }
    }

    /**
     * @return Collection|Wallet[]
     */
    public function getWallets(): Collection
    {
        return $this->wallets;
    }

    /**
     * @param Wallet $wallet
     *
     * @return $this
     */
    public function addWallet(Wallet $wallet): self
    {
        if (!$this->wallets->contains($wallet)) {
            $this->wallets[] = $wallet;
            $wallet->setGroup($this);
        }

        return $this;
    }

    /**
     * @param Wallet $wallet
     *
     * @return $this
     */
    public function removeWallet(Wallet $wallet): self
    {
        if ($this->wallets->contains($wallet)) {
            $this->wallets->removeElement($wallet);
            // set the owning side to null (unless already changed)
            if ($wallet->getGroup() === $this) {
                $wallet->setGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return float|null
     */
    public function getBudget(): ?float
    {
        return $this->budget;
    }

    /**
     * @param float|null $budget
     */
    public function setBudget(?float $budget): void
    {
        $this->budget = $budget;
    }
}
