<?php


namespace App\Security\Voter;


use App\Entity\Payment;
use App\Entity\User;
use App\Manager\WalletManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Class PaymentVoter
 *
 * @package App\Security\Voter
 */
class PaymentVoter extends Voter
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var WalletManager
     */
    private $walletManager;

    /**
     * PaymentVoter constructor.
     *
     * @param Security $security
     * @param WalletManager $walletManager
     */
    public function __construct(Security $security, WalletManager $walletManager)
    {
        $this->security = $security;
        $this->walletManager = $walletManager;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject): bool
    {
        // only vote on Post objects inside this voter
        if (!$subject instanceof Payment) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param Payment $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($this->security->isGranted('ROLE_USER')) {
            return ($this->walletManager->hasAccess($user, $subject->getWallet()));
        }

        return false;
    }
}