<?php


namespace App\Manager;


use App\Entity\Group;
use App\Entity\GroupUserConfiguration;
use App\Entity\User;
use App\Entity\UserInvitation;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class GroupManager
 * @package App\Manager
 */
class GroupManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * GroupManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param MailerInterface $mailer
     * @param Environment $environment
     * @param RouterInterface $router
     * @param FlashBagInterface $flashBag
     */
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer
        , Environment $environment, RouterInterface $router, FlashBagInterface $flashBag)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->environment = $environment;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Group $group
     * @param User $user
     * @return bool
     */
    public function addGroup(Group $group, User $user): bool
    {
        try {
            $this->entityManager->persist($group);
            $groupConfig = new GroupUserConfiguration($group, $user, GroupUserConfiguration::GROUP_ADMINISTRATOR);
            $user->addGroupUserConfiguration($groupConfig);
            $this->entityManager->persist($groupConfig);

            // create group wallet
            $wallet = new Wallet();
            $wallet->setName('Geldbeutel');
            $wallet->setGroup($group);
            $this->entityManager->persist($wallet);

            $this->entityManager->flush();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param User $me
     * @param User $profile
     *
     * @return bool
     */
    public function checkGroupMembers(User $me, User $profile): bool
    {
        $result = false;

        if ($me === $profile) {
            return true;
        }

        foreach($me->getGroups() as $groupMe) {
            foreach($profile->getGroups() as $groupOther) {
                if ($groupMe->getGroup() === $groupOther->getGroup()) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * @param Group $group
     * @param User $user
     * @return bool
     */
    public function isMember(Group $group, User $user): bool
    {
        foreach ($user->getGroups() as $userGroup)
        {
            if ($userGroup->getGroup() === $group) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param User $user
     * @param Group $group
     *
     * @return bool
     */
    public function isAdmin(User $user, Group $group)
    {
        $groupConfiguration = $this->entityManager->getRepository(GroupUserConfiguration::class)->findOneBy(['user' => $user, 'group' => $group]);
        return ($groupConfiguration->getRole() === GroupUserConfiguration::GROUP_ADMINISTRATOR);
    }

    /**
     * @param UserInvitation $invitation
     * @param Group $group
     * @return bool
     */
    public function inviteUser(UserInvitation $invitation, Group $group): bool
    {
        try {
            $token = md5(random_bytes(10));

            $invitation->setGroup($group);
            $invitation->setToken($token);

            $tokenUrl = $this->router->generate('app_register', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $this->entityManager->persist($invitation);
            $this->entityManager->flush();

            $email = (new Email())
                ->from('no-reply@simple-student.de')
                ->to($invitation->getEmail())
                ->subject('Deine Einladung zu HomeJobs!')
                ->html($this->environment->render('mails/invitation.html.twig', ['url' => $tokenUrl]));
            $this->mailer->send($email);

            $this->flashBag->add('success', 'Die Einladung war erfolgreich');
            return true;

        } catch (LoaderError $e) {
            $this->flashBag->add('error', $e->getMessage());
            return false;
        } catch (RuntimeError $e) {
            $this->flashBag->add('error', $e->getMessage());
            return false;
        } catch (SyntaxError $e) {
            $this->flashBag->add('error', $e->getMessage());
            return false;
        } catch (TransportExceptionInterface $e) {
            $this->flashBag->add('error', $e->getMessage());
            return false;
        } catch (Exception $e) {
            $this->flashBag->add('error', $e->getMessage());
            return false;
        }
    }
}