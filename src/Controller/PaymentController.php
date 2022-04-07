<?php


namespace App\Controller;

use App\Entity\Payment;
use App\Entity\User;
use App\Entity\Wallet;
use App\Form\Request\PaymentRequest;
use App\Form\Type\PaymentType;
use App\Manager\WalletManager;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment")
 *
 * @IsGranted("ROLE_USER")
 *
 * Class PaymentController
 *
 * @package App\Controller
 */
class PaymentController extends AbstractController
{
    /**
     * @Route("/add/{wallet}", name="payment_add")
     *
     * @param Request $request
     * @param Wallet $wallet
     *
     * @return Response
     * @throws Exception
     */
    public function add(Request $request, Wallet $wallet): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $payment = new Payment();
        $payment->setWallet($wallet);
        $payment->setCreator($user);

        // check permissions
        $this->denyAccessUnlessGranted('edit', $payment);

        $paymentRequest = new PaymentRequest($payment);
        $form = $this->createForm(PaymentType::class, $paymentRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentRequest->fillEntity($payment);

            $this->getDoctrine()->getManager()->persist($payment);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Die Ausgabe wurde erfolgreich eingetragen!');
            return $this->redirectToRoute('wallet_index', ['wallet' => $wallet->getId()]);
        }

        return $this->render('payment/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{payment}", name="payment_edit")
     *
     * @param Request $request
     * @param Payment $payment
     *
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, Payment $payment): Response
    {
        // check permissions
        $this->denyAccessUnlessGranted('edit', $payment);

        $paymentRequest = new PaymentRequest($payment);
        $form = $this->createForm(PaymentType::class, $paymentRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentRequest->fillEntity($payment);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Die Ausgabe wurde erfolgreich eingetragen!');
            return $this->redirectToRoute('wallet_index', ['wallet' => $payment->getWallet()->getId()]);
        }

        return $this->render('payment/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/remove/{payment}", name="payment_remove")
     *
     * @param Payment $payment
     *
     * @return Response
     */
    public function remove(Payment $payment): Response
    {
        $this->denyAccessUnlessGranted('remove', $payment);
        $wallet = $payment->getWallet();
        $this->getDoctrine()->getManager()->remove($payment);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Die Ausgabe wurde erfolgreich gelöscht!');
        return $this->redirectToRoute('wallet_index', ['wallet' => $wallet->getId()]);
    }
}