<?php


namespace App\Controller;


use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 *
 * @package App\Controller
 *
 * @IsGranted("ROLE_USER")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     *
     * @return Response
     *
     * @throws Exception
     */
    public function index(): Response
    {
        $now = new DateTime();
        return $this->render('default/index.html.twig', ['now' => $now]);
    }
}