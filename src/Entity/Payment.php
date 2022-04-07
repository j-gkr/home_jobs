<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 * @ORM\Table(name="hj_payment")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable()
 * @JMS\ExclusionPolicy("all")
 */
class Payment
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Wallet", inversedBy="payments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wallet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="payments")
     */
    private $creator;

    /**
     * @ORM\Column(type="float")
     * @Gedmo\Versioned()
     * @JMS\Expose()
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Expose()
     */
    private $description;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     * @JMS\Expose()
     */
    private $paymentDate;

    /**
     * @var PaymentCategory
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentCategory", inversedBy="payments")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     * @JMS\Expose()
     */
    private $paymentCategory;

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
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Wallet|null
     */
    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    /**
     * @param Wallet|null $wallet
     *
     * @return $this
     */
    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    /**
     * @param User|null $creator
     *
     * @return $this
     */
    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return $this
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

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
     * @param string|null $description
     *
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getPaymentDate(): ?DateTime
    {
        return $this->paymentDate;
    }

    /**
     * @param DateTime $paymentDate
     */
    public function setPaymentDate(DateTime $paymentDate): void
    {
        $this->paymentDate = $paymentDate;
    }

    /**
     * @return PaymentCategory|null
     */
    public function getPaymentCategory(): ?PaymentCategory
    {
        return $this->paymentCategory;
    }

    /**
     * @param PaymentCategory|null $paymentCategory
     *
     * @return $this
     */
    public function setPaymentCategory(?PaymentCategory $paymentCategory): self
    {
        $this->paymentCategory = $paymentCategory;

        return $this;
    }
}
