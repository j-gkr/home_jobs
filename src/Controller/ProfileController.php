<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\Request\UserRequest;
use App\Form\Type\UserType;
use App\Manager\ActivityManager;
use App\Manager\GroupManager;
use App\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 *
 * @package App\Controller
 *
 * @Route("/profile")
 *
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{

    /**
     * @Route("/show/{user}", name="profile_show")
     *
     * @param User $user
     * @param GroupManager $groupManager
     * @param ActivityManager $activityManager
     *
     * @return Response
     */
    public function show(User $user, GroupManager $groupManager, ActivityManager $activityManager): Response
    {
        /** @var User $me */
        $me = $this->getUser();

        if (!$groupManager->checkGroupMembers($me, $user)) {
            throw $this->createAccessDeniedException('You are not in the same group!');
        } else {
            $activities = $activityManager->loadLastActivitiesByUser($user);

            return $this->render('profile/show.html.twig', ['user' => $user, 'activities' => $activities]);
        }
    }

    /**
     * @Route("/edit", name="profile_edit")
     *
     * @param Request $request
     * @param UserManager $userManager
     * @param ActivityManager $activityManager
     *
     * @return Response
     */
    public function edit(Request $request, UserManager $userManager, ActivityManager $activityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userRequest = new UserRequest($user);
        $activities = $activityManager->loadLastActivitiesByUser($user);
        $form = $this->createForm(UserType::class, $userRequest, ['withAvatar' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRequest->fillEntity($user);

            if (null !== $userRequest->getAvatarFile()) {
                if (!$userManager->uploadProfileImage($userRequest, $user)) {
                    $this->addFlash('error', 'Fehler beim Hochladen des Profilbilds.');
                }
            }

            $this->getDoctrine()->getManager()->merge($user);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Ihr Profil wurde erfolgreich aktualisiert!');
            return $this->redirectToRoute('profile_edit');
        }
        return $this->render('profile/edit.html.twig', ['form' => $form->createView(), 'activities' => $activities]);
    }
}