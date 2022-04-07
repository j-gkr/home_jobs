<?php


namespace App\Controller;


use App\Entity\Payment;
use App\Entity\PaymentCategory;
use App\Entity\User;
use App\Entity\Wallet;
use App\Manager\WalletManager;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wallet")
 *
 * @IsGranted("ROLE_USER")
 *
 * Class WalletController
 * @package App\Controller
 */
class WalletController extends AbstractController
{
    /**
     * @Route("/{wallet}", name="wallet_index")
     *
     * @param Request $request
     * @param Wallet $wallet
     * @param WalletManager $walletManager
     *
     * @return Response
     * @throws Exception
     */
    public function index(Request $request, Wallet $wallet, WalletManager $walletManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$walletManager->hasAccess($user, $wallet)) {
            throw $this->createAccessDeniedException();
        }

        $from = new DateTime('first day of this month');
        $to = new DateTime('last day of this month');
        $from->setTime(0,0,0);
        $to->setTime(23,59,59);

        if ($request->isMethod('post')) {
            if ($request->request->has('from') && $request->request->has('to')) {
                $from = DateTime::createFromFormat('Y-m-d', $request->request->get('from'));
                $to = DateTime::createFromFormat('Y-m-d', $request->request->get('to'));
                $from->setTime(0,0,0);
                $to->setTime(23,59,59);
            }
        }

        $payments = $this->getDoctrine()->getManager()->getRepository(Payment::class)->loadByPeriod($wallet, $from, $to);
        $paymentCategories = $this->getDoctrine()->getManager()->getRepository(PaymentCategory::class)->loadByPeriodWithSum($wallet, $from, $to);

        $totalSum = 0;

        foreach ($paymentCategories as $category) {
            $totalSum += $category['sum'];
        }

        return $this->render('wallet/index.html.twig', ['wallet' => $wallet, 'payments' => $payments, 'categories' => $paymentCategories, 'from' => $from, 'to' => $to, 'totalSum' => $totalSum]);
    }
}