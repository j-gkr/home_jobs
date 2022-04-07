<?php


namespace App\Form\Request;

use App\Entity\Payment;
use App\Entity\PaymentCategory;
use App\Entity\User;
use App\Entity\Wallet;
use DateTime;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PaymentRequest
 * @package App\Form\Request
 */
class PaymentRequest
{
    /**
     * @var string
     *
     * @Assert\Type(type="string", message="Bitte geben Sie eine gültige Eingabe an.")
     * @Assert\Length(max="255", maxMessage="Bitte geben Sie maximal 255 Zeichen ein.")
     * @Assert\NotBlank(message="Bitte geben Sie einen Namen an.")
     * @Assert\NotNull(message="Bitte geben Sie einen Namen an.")
     */
    private $name;

    /**
     * @var Wallet|null
     */
    private $wallet;

    /**
     * @var User|null
     */
    private $creator;

    /**
     * @var float|null
     *
     * @Assert\Type(type="float", message="Bitte geben Sie einen Zahlenwert an.")
     * @Assert\NotNull(message="Bitte geben Sie einen Betrag an.")
     */
    private $amount;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var DateTime|null
     *
     * @Assert\Date(message="Bitte geben Sie ein gültiges Datum an.")
     */
    private $paymentDate;

    /**
     * @var PaymentCategory
     */
    private $paymentCategory;

    /**
     * PaymentRequest constructor.
     *
     * @param Payment|null $payment
     * @throws Exception
     */
    public function __construct(Payment $payment = null)
    {
        if ($payment !== null) {
            $this->extractEntity($payment);
        }

        if ($this->paymentDate === null) {
            $this->paymentDate = new DateTime();
        }
    }

    /**
     * @param Payment $payment
     */
    public function extractEntity(Payment $payment): void
    {
        $this->setDescription($payment->getDescription());
        $this->setAmount($payment->getAmount());
        $this->setCreator($payment->getCreator());
        $this->setName($payment->getName());
        $this->setPaymentDate($payment->getPaymentDate());
        $this->setWallet($payment->getWallet());
        $this->setPaymentCategory($payment->getPaymentCategory());
    }

    /**
     * @param Payment $payment
     *
     * @return Payment
     */
    public function fillEntity(Payment $payment): Payment
    {
        $payment->setWallet($this->getWallet());
        $payment->setPaymentDate($this->getPaymentDate());
        $payment->setName($this->getName());
        $payment->setCreator($this->getCreator());
        $payment->setAmount($this->getAmount());
        $payment->setDescription($this->getDescription());
        $payment->setPaymentCategory($this->getPaymentCategory());

        return $payment;
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
     * @return Wallet|null
     */
    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    /**
     * @param Wallet|null $wallet
     */
    public function setWallet(?Wallet $wallet): void
    {
        $this->wallet = $wallet;
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
     */
    public function setCreator(?User $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float|null $amount
     */
    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
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
     * @return DateTime|null
     */
    public function getPaymentDate(): ?DateTime
    {
        return $this->paymentDate;
    }

    /**
     * @param DateTime|null $paymentDate
     */
    public function setPaymentDate(?DateTime $paymentDate): void
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
     */
    public function setPaymentCategory(?PaymentCategory $paymentCategory): void
    {
        $this->paymentCategory = $paymentCategory;
    }

}