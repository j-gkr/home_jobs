<?php


namespace App\Controller;


use App\Entity\Group;
use App\Entity\ScheduledHomeJob;
use App\Entity\User;
use App\Form\Request\ScheduledHomeJobRequest;
use App\Form\Type\ScheduledHomeJobType;
use App\Manager\GroupManager;
use App\Manager\ScheduledHomeJobManager;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeJobController
 *
 * @package App\Controller
 *
 * @IsGranted("ROLE_USER")
 * @Route("/scheduled-home-job")
 */
class ScheduledHomeJobController extends AbstractController
{
    /**
     * @Route("/{group}/", name="scheduled_home_job_index")
     *
     * @param Group $group
     *
     * @return Response
     */
    public function index(Group $group): Response
    {
        $jobs = $this->getDoctrine()->getRepository(ScheduledHomeJob::class)->findBy(['group' => $group->getId()]);
        return $this->render('scheduledHomeJob/index.html.twig', ['jobs' => $jobs, 'group' => $group]);
    }

    /**
     * @Route("/add/{group}", name="scheduled_home_job_add")
     *
     * @param Request $request
     * @param Group $group
     * @param ScheduledHomeJobManager $scheduledHomeJobManager
     * @param GroupManager $groupManager
     *
     * @return Response
     * @throws Exception
     */
    public function add(Request $request, Group $group, ScheduledHomeJobManager $scheduledHomeJobManager, GroupManager $groupManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$groupManager->isMember($group, $user)) {
            throw $this->createAccessDeniedException('You are not member of the group!');
        }

        $job = new ScheduledHomeJob();
        $jobRequest = new ScheduledHomeJobRequest($job);
        $groupUsers = $this->getDoctrine()->getRepository(User::class)->loadByGroup($group);
        $form = $this->createForm(ScheduledHomeJobType::class, $jobRequest, ['users' => $groupUsers]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobRequest->fillEntity($job);
            $job->setGroup($group);
            $this->getDoctrine()->getManager()->persist($job);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Die Aufgabe wurde erfolgreich erstellt!');
            $scheduledHomeJobManager->generateHomeJobs();
            return $this->redirectToRoute('scheduled_home_job_index', ['group' => $group->getId()]);
        }

        return $this->render('scheduledHomeJob/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{group}/{scheduledHomeJob}", name="scheduled_home_job_edit")
     *
     * @param Request $request
     * @param Group $group
     * @param ScheduledHomeJob $scheduledHomeJob
     * @param GroupManager $groupManager
     * @param ScheduledHomeJobManager $scheduledHomeJobManager
     *
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, Group $group, ScheduledHomeJob $scheduledHomeJob, GroupManager $groupManager, ScheduledHomeJobManager $scheduledHomeJobManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$groupManager->isMember($group, $user)) {
            throw $this->createAccessDeniedException('You are not member of the group!');
        }

        $jobRequest = new ScheduledHomeJobRequest($scheduledHomeJob);
        $groupUsers = $this->getDoctrine()->getRepository(User::class)->loadByGroup($group);
        $form = $this->createForm(ScheduledHomeJobType::class, $jobRequest, ['users' => $groupUsers]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobRequest->fillEntity($scheduledHomeJob);
            $scheduledHomeJob->setGroup($group);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Die Aufgabe wurde erfolgreich aktualisiert!');
            $scheduledHomeJobManager->generateHomeJobs();
            return $this->redirectToRoute('scheduled_home_job_index', ['group' => $group->getId()]);
        }

        return $this->render('scheduledHomeJob/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/remove/{group}/{scheduledHomeJob}", name="scheduled_home_job_remove")
     *
     * @param ScheduledHomeJob $scheduledHomeJob
     * @param Group $group
     * @param ScheduledHomeJobManager $homeJobManager
     *
     * @return Response
     */
    public function remove(ScheduledHomeJob $scheduledHomeJob, Group $group, ScheduledHomeJobManager $homeJobManager): Response
    {
        if ($homeJobManager->delete($scheduledHomeJob)) {
            $this->addFlash('success', 'Die Aufgabe wurde erfolgreich gelöscht!');
        } else {
            $this->addFlash('error', 'Beim Löschen ist ein Fehler aufgetreten!');
        }
        return $this->redirectToRoute('scheduled_home_job_index', ['group' => $group->getId()]);
    }

}