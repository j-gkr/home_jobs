<?php


namespace App\Manager;


use App\Entity\GroupUserConfiguration;
use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class WalletManager
 * @package App\Manager
 * @author Julian Gebker
 * @version 1.0.0
 */
class WalletManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * WalletManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @param Wallet $wallet
     * @return bool
     */
    public function hasAccess(User $user, Wallet $wallet): bool
    {
        // check for group wallet
        if ($wallet->getGroup() !== null) {
            $ids = $this->entityManager->getRepository(GroupUserConfiguration::class)->loadGroupIdsByUser($user);
            $ids = array_column($ids, 'id');
            return in_array($wallet->getGroup()->getId(), $ids);
        }
        // check for private wallet
        if ($wallet->getOwner() !== null) {
            return ($wallet->getOwner() === $user);
        }
        // still here? -> no access...
        return false;
    }
}