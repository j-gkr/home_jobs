<?php

namespace App\Controller;

use App\Entity\GroupUserConfiguration;
use App\Entity\User;
use App\Entity\UserInvitation;
use App\Form\Request\UserRequest;
use App\Form\Type\UserType;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Handles login, logout and register.
 *
 * Class SecurityController
 *
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error !== null) {
            $this->addFlash('error', 'Ungültige Benutzerdaten!');
        }
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        //throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register", name="app_register")
     *
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     *
     * @param Request $request
     * @param MailerInterface $mailer
     *
     * @return Response
     */
    public function register(Request $request, MailerInterface $mailer): Response
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('default');
        }

        $invitation = null;

        if ($request->query->has('token')) {
            $token = $request->query->get('token');
            $invitation = $this->getDoctrine()->getRepository(UserInvitation::class)->findOneBy(['token' => $token]);
        }

        $user = new User();
        $userRequest = new UserRequest($user);

        $form = $this->createForm(UserType::class, $userRequest, ['validation_groups' => 'registration']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRequest->fillEntity($user);

            try {
                // create token for activation and generate url
                $token = md5(random_bytes(10));
                $tokenUrl = $this->generateUrl('app_activate', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                $user->setToken($token);

                $email = (new Email())
                    ->from('no-reply@simple-student.de')
                    ->to($userRequest->getUsername())
                    ->subject('Dein Konto bei HomeJobs!')
                    ->html($this->renderView('mails/activation.html.twig', ['url' => $tokenUrl]));

                // send mail
                $mailer->send($email);
            } catch (Exception $e) {
                $this->addFlash('error', 'Fehler bei der Erstellung des Tokens!');
                return $this->redirectToRoute('app_login');
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('error', sprintf('E-Mail konnte nicht gesendet werden! Meldung: %s', $e->getMessage()));
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('success', 'Ihr Konto wurde erstellt. Überprüfen Sie Ihre E-Mails und bestätigen Ihre E-Mail Adresse!');
            $this->getDoctrine()->getManager()->persist($user);

            if ($invitation !== null) {
                $groupUserConfiguration = new GroupUserConfiguration($invitation->getGroup(), $user, GroupUserConfiguration::GROUP_MEMBER);
                $user->addGroupUserConfiguration($groupUserConfiguration);
                $this->getDoctrine()->getManager()->persist($groupUserConfiguration);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/activate", name="app_activate")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function activate(Request $request): Response
    {
        if ($request->query->has('token')) {
            try {
                $token = $request->query->get('token');
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['token' => $token]);

                if(null !== $user) {
                    $user->setActive(true);
                    $this->getDoctrine()->getManager()->flush();
                    $this->addFlash('success', 'Dein Benutzerkonto wurde erfolgreich aktiviert! Du kannst dich nun einloggen!');
                    return $this->render('security/activation.html.twig', ['user' => $user]);
                } else {
                    $this->addFlash('error', 'Es wurde kein Benutzer gefunden!');
                    return $this->redirectToRoute('app_login');
                }
            } catch (Exception $exception) {
                $this->addFlash('error', 'Fehler bei der Aktivierung!');
                return $this->redirectToRoute('app_login');
            }
        } else {
            throw new BadRequestHttpException('Bad Request.');
        }
    }
}
