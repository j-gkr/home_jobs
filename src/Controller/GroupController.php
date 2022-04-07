<?php


namespace App\Controller;


use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserInvitation;
use App\Form\Request\GroupRequest;
use App\Form\Request\UserInvitationRequest;
use App\Form\Type\GroupType;
use App\Form\Type\UserInvitationType;
use App\Manager\GroupManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupController
 *
 * @package App\Controller
 *
 * @Route("/group")
 *
 * @IsGranted("ROLE_USER")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/add", name="group_add")
     *
     * @param Request $request
     * @param GroupManager $groupManager
     *
     * @return Response
     */
    public function add(Request $request, GroupManager $groupManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getGroups()->count() > 0) {
            $this->addFlash('info', 'Sie sind bereits Mitglied einer Gruppe.');
            return $this->redirectToRoute('default');
        }

        $group = new Group();
        $groupRequest = new GroupRequest($group);
        $form = $this->createForm(GroupType::class, $groupRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupRequest->fillEntity($group);

            if ($groupManager->addGroup($group, $user)) {
                $this->addFlash('success', 'Die Gruppe wurde erfolgreich erstellt!');
            } else {
                $this->addFlash('error', 'Die Gruppe konnte nicht erstellt werden!');
            }

            return $this->redirectToRoute('default');
        }

        return $this->render('group/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/members/{group}", name="group_members")
     *
     * @param Group $group
     *
     * @return Response
     */
    public function members(Group $group): Response
    {
        $members = $this->getDoctrine()->getRepository(User::class)->loadByGroup($group);
        return $this->render('group/members.html.twig', ['members' => $members, 'group' => $group]);
    }

    /**
     * @Route("/invite/{group}", name="group_invite_user")
     *
     * @param Request $request
     * @param Group $group
     * @param GroupManager $groupManager
     *
     * @return Response
     */
    public function inviteUser(Request $request, Group $group, GroupManager $groupManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$groupManager->isAdmin($user, $group)) {
            throw $this->createAccessDeniedException('You are not an admin!');
        }

        $invitation = new UserInvitation();
        $invitationRequest = new UserInvitationRequest($invitation);
        $form = $this->createForm(UserInvitationType::class, $invitationRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invitationRequest->fillEntity($invitation);
            $groupManager->inviteUser($invitation, $group);
            return $this->redirectToRoute('group_members', ['group' => $group->getId()]);
        }

        return $this->render('group/invitation.html.twig', ['form' => $form->createView(), 'group' => $group]);
    }
}