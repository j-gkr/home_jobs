<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as JMS;
/**
 * Class represents a system user.
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="hj_user")
 * @JMS\ExclusionPolicy("all")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @JMS\Expose()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    private $lastName;

    /**
     * @var array
     *
     * @ORM\Column(type="json", nullable=true)
     * @JMS\Expose()
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\COlumn(type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Expose()
     */
    private $avatarFile;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @JMS\Expose()
     */
    private $active;

    /**
     * @var Collection|HomeJob[]|null
     *
     * @ORM\OneToMany(targetEntity="App\Entity\HomeJob", mappedBy="editor")
     */
    private $homeJobs;

    /**
     * @var Collection|ScheduledHomeJob[]|null
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ScheduledHomeJob", mappedBy="editor")
     */
    private $scheduledHomeJobs;

    /**
     * @var GroupUserConfiguration[]|Collection|null
     *
     * @ORM\OneToMany(targetEntity="App\Entity\GroupUserConfiguration", mappedBy="user")
     * @JMS\Expose()
     */
    private $groups;

    /**
     * @var Collection|Wallet[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Wallet", mappedBy="owner")
     */
    private $wallets;

    /**
     * @var Collection|Payment[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Payment", mappedBy="creator")
     */
    private $payments;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="enabled_job_notification", type="boolean", nullable=true)
     * @JMS\Expose()
     */
    private $enabledJobNotification;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="enabled_payment_notification", type="boolean", nullable=true)
     * @JMS\Expose()
     */
    private $enabledPaymentNotification;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->enabledPaymentNotification = false;
        $this->enabledJobNotification = false;
        $this->groups = new ArrayCollection();
        $this->roles = [];
        $this->active = false;
        $this->wallets = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

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
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array
     */
    public function getRoles(): array
    {
        return array_unique(array_merge(['ROLE_USER'], $this->roles));
    }

    /**
     * @param array $roles
     *
     * @return void
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Resets all user roles
     *
     * @return void
     */
    public function resetRoles(): void
    {
        $this->roles = [];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @param string $salt
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }

    public function getFullNameWithMail(): string
    {
        return sprintf('%s %s (%s)', $this->getFirstName(), $this->getLastName(), $this->getUsername());
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string|null
     */
    public function getAvatarFile(): ?string
    {
        return $this->avatarFile;
    }

    /**
     * @param string|null $avatarFile
     */
    public function setAvatarFile(?string $avatarFile): void
    {
        $this->avatarFile = $avatarFile;
    }

    /**
     * @return GroupUserConfiguration[]|Collection|null
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return HomeJob[]|Collection|null
     */
    public function getHomeJobs()
    {
        return $this->homeJobs;
    }

    /**
     * @param GroupUserConfiguration $configuration
     * @param bool $stop
     */
    public function addGroupUserConfiguration(GroupUserConfiguration $configuration, bool $stop = false)
    {
        if (!$this->groups->contains($configuration)) {
            $this->groups->add($configuration);
        }

        if (!$stop) {
            $configuration->setUser($this, true);
        }
    }

    /**
     * @param GroupUserConfiguration $configuration
     * @param bool $stop
     */
    public function removeGroupUserConfiguration(GroupUserConfiguration $configuration, bool $stop = false)
    {
        if ($this->groups->contains($configuration)) {
            $this->groups->removeElement($configuration);
        }

        if (!$stop) {
            $configuration->setUser(null, true);
        }
    }

    /**
     * @param HomeJob[]|Collection|null $homeJobs
     */
    public function setHomeJobs($homeJobs): void
    {
        $this->homeJobs = $homeJobs;
    }

    /**
     * @return ScheduledHomeJob[]|Collection|null
     */
    public function getScheduledHomeJobs()
    {
        return $this->scheduledHomeJobs;
    }

    /**
     * @param ScheduledHomeJob[]|Collection|null $scheduledHomeJobs
     */
    public function setScheduledHomeJobs($scheduledHomeJobs): void
    {
        $this->scheduledHomeJobs = $scheduledHomeJobs;
    }

    /**
     * @return Collection|Wallet[]
     */
    public function getWallets(): Collection
    {
        return $this->wallets;
    }

    public function addWallet(Wallet $wallet): self
    {
        if (!$this->wallets->contains($wallet)) {
            $this->wallets[] = $wallet;
            $wallet->setOwner($this);
        }

        return $this;
    }

    public function removeWallet(Wallet $wallet): self
    {
        if ($this->wallets->contains($wallet)) {
            $this->wallets->removeElement($wallet);
            // set the owning side to null (unless already changed)
            if ($wallet->getOwner() === $this) {
                $wallet->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    /**
     * @param Payment $payment
     *
     * @return $this
     */
    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setCreator($this);
        }

        return $this;
    }

    /**
     * @param Payment $payment
     *
     * @return $this
     */
    public function removePayment(Payment $payment): self
    {
        if ($this->payments->contains($payment)) {
            $this->payments->removeElement($payment);
            // set the owning side to null (unless already changed)
            if ($payment->getCreator() === $this) {
                $payment->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isEnabledJobNotification(): ?bool
    {
        return $this->enabledJobNotification;
    }

    /**
     * @param bool|null $enabledJobNotification
     */
    public function setEnabledJobNotification(?bool $enabledJobNotification): void
    {
        $this->enabledJobNotification = $enabledJobNotification;
    }

    /**
     * @return bool|null
     */
    public function isEnabledPaymentNotification(): ?bool
    {
        return $this->enabledPaymentNotification;
    }

    /**
     * @param bool|null $enabledPaymentNotification
     */
    public function setEnabledPaymentNotification(?bool $enabledPaymentNotification): void
    {
        $this->enabledPaymentNotification = $enabledPaymentNotification;
    }
}
