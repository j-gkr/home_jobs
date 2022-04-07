<?php


namespace App\Twig;


use App\Entity\Group;
use App\Entity\GroupUserConfiguration;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class WidgetExtension
 *
 * Renders widgets in templates/partials/widgets
 *
 * @package App\Twig
 */
class WidgetExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * WidgetExtension constructor.
     * @param EntityManagerInterface $entityManager
     * @param Environment $environment
     */
    public function __construct(EntityManagerInterface $entityManager, Environment $environment)
    {
        $this->entityManager = $entityManager;
        $this->environment = $environment;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('renderOpenHomeJobs', [$this, 'renderOpenHomeJobs']),
            new TwigFunction('renderLastExecutedHomeJobs', [$this, 'renderLastExecutedHomeJobs']),
            new TwigFunction('renderGroupMember', [$this, 'renderGroupMember']),
            new TwigFunction('renderDueHomeJobs', [$this, 'renderDueHomeJobs']),
        ];
    }

    /**
     * @param User $user
     * @return string
     */
    public function renderDueHomeJobs(User $user): string
    {
        // important to clear cache here!!!
        $this->entityManager->clear();

        $groupIds = $this->entityManager->getRepository(GroupUserConfiguration::class)->loadGroupIdsByUser($user);

        if (!empty($groupIds)) {
            $groups = $this->entityManager->getRepository(Group::class)->loadDueHomeJobsByGroups($groupIds);
        } else {
            $groups = new ArrayCollection();
        }

        try {
            return $this->environment->render(
                'partials/widgets/dueHomeJobs.html.twig',
                ['dueHomeJobGroups' => $groups]
            );
        } catch (LoaderError $e) {
            return '';
        } catch (RuntimeError $e) {
            return '';
        } catch (SyntaxError $e) {
            return '';
        }
    }

    /**
     * @param User $user
     * @return string
     */
    public function renderOpenHomeJobs(User $user): string
    {
        // important to clear cache here!!!
        $this->entityManager->clear();

        $groupIds = $this->entityManager->getRepository(GroupUserConfiguration::class)->loadGroupIdsByUser($user);

        if (!empty($groupIds)) {
            $groups = $this->entityManager->getRepository(Group::class)->loadOpenHomeJobsByGroups($groupIds);
        } else {
            $groups = new ArrayCollection();
        }

        try {
            return $this->environment->render(
                'partials/widgets/openHomeJobs.html.twig',
                ['openHomeJobGroups' => $groups]
            );
        } catch (LoaderError $e) {
            return '';
        } catch (RuntimeError $e) {
            return '';
        } catch (SyntaxError $e) {
            return '';
        }
    }

    /**
     * @param User $user
     * @return string
     */
    public function renderLastExecutedHomeJobs(User $user): string
    {
        // important to clear cache here!!!
        $this->entityManager->clear();

        $groupIds = $this->entityManager->getRepository(GroupUserConfiguration::class)->loadGroupIdsByUser($user);

        if (!empty($groupIds)) {
            $groups = $this->entityManager->getRepository(Group::class)->loadLastExecutedHomeJobsByGroups($groupIds);
        } else {
            $groups = new ArrayCollection();
        }

        try {
            return $this->environment->render(
                'partials/widgets/lastExecutedJobs.html.twig',
                ['lastExecutedGroups' => $groups]
            );
        } catch (LoaderError $e) {
            return '';
        } catch (RuntimeError $e) {
            return '';
        } catch (SyntaxError $e) {
            return '';
        }
    }

    /**
     * @param User $user
     * @return string
     */
    public function renderGroupMember(User $user): string
    {
        // important to clear cache here!!!
        $this->entityManager->clear();

        $groupIds = $this->entityManager->getRepository(GroupUserConfiguration::class)->loadGroupIdsByUser($user);

        $member = $this->entityManager->getRepository(Group::class)->loadUserByGroups($groupIds);

        try {
            return $this->environment->render(
                'partials/widgets/groupMember.html.twig',
                ['memberGroups' => $member]
            );
        } catch (LoaderError $e) {
            return '';
        } catch (RuntimeError $e) {
            return '';
        } catch (SyntaxError $e) {
            return '';
        }
    }
}