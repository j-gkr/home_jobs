<?php


namespace App\Controller;


use App\Entity\HomeJob;
use App\Entity\User;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeJobController
 *
 * @Route("/home-job")
 *
 * @IsGranted("ROLE_USER")
 *
 * @package App\Controller
 */
class HomeJobController extends AbstractController
{

    /**
     * @Route("/show/{homeJob}", name="home_job_show")
     *
     * @param HomeJob $homeJob
     *
     * @return Response
     */
    public function show(HomeJob $homeJob): Response
    {
        return $this->render('homeJob/show.html.twig', ['homeJob' => $homeJob]);
    }

    /**
     * @Route("/execute/{homeJob}", name="home_job_execute")
     *
     * @param HomeJob $homeJob
     * @return Response
     * @throws Exception
     */
    public function executeJob(HomeJob $homeJob): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($homeJob->getEditor() !== $user) {
            throw $this->createAccessDeniedException('You are not the editor for this job!');
        }

        if ($homeJob->getExecutionDate() !== null) {
            $this->addFlash('warning', 'Hoppla! Diese Aufgabe wurde bereits erledigt!');
            return $this->redirectToRoute('default');
        }

        $executionDate = new DateTime();
        $homeJob->setExecutionDate($executionDate);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Aufgabe wurde erfolgreich als erledigt markiert!');
        return $this->redirectToRoute('default');
    }

}