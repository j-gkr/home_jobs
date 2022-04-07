<?php


namespace App\Manager;


use App\Entity\HomeJob;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class NotificationManager
 *
 * @package App\Manager
 */
class NotificationManager
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
     * NotificationManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param MailerInterface $mailer
     * @param Environment $environment
     */
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer, Environment $environment)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->environment = $environment;
    }

    /**
     * @return bool
     *
     * @throws Exception
     */
    public function sendMailNotifications(): bool
    {
        $users = $this->entityManager->getRepository(User::class)->loadActiveUser();

        foreach($users as $user) {
            try {
                if ($user->isEnabledJobNotification()) {
                    $openHomeJobs = $this->entityManager->getRepository(HomeJob::class)->loadOpenJobsByUser($user);
                    $dueHomeJobs = $this->entityManager->getRepository(HomeJob::class)->loadDueJobsByUser($user);

                    if ((!empty($openHomeJobs)) || (!empty($dueHomeJobs)))
                    {
                        $now = new DateTime();
                        $email = (new Email())
                            ->from('no-reply@simple-student.de')
                            ->to($user->getUsername())
                            ->subject('HomeJobs | Erinnerung zu deinen Aufgaben')
                            ->html($this->environment->render('mails/homeJobMailNotification.html.twig', ['user' => $user, 'homeJobs' => $openHomeJobs, 'dueJobs' => $dueHomeJobs, 'now' => $now]));

                        $this->mailer->send($email);
                    }
                }
            } catch (LoaderError $e) {
                return false;
            } catch (RuntimeError $e) {
                return false;
            } catch (SyntaxError $e) {
                return false;
            } catch (TransportExceptionInterface $e) {
                return false;
            }
        }

        return true;
    }
}