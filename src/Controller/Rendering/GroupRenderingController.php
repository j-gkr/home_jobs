<?php


namespace App\Controller\Rendering;


use App\Entity\GroupUserConfiguration;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupRenderingController
 *
 * @Route("/render-groups")
 *
 * @IsGranted("ROLE_USER")
 *
 * @package App\Controller\Rendering
 */
class GroupRenderingController extends AbstractController
{
    /**
     * @Route("/", name="render_groups")
     *
     * @return Response
     */
    public function renderGroups(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $groups = $this->getDoctrine()->getRepository(GroupUserConfiguration::class)->loadByUser($user);
        return $this->render('partials/render/groupMenu.html.twig', ['groups' => $groups]);
    }
}