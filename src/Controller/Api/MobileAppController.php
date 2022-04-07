<?php


namespace App\Controller\Api;

use App\Entity\Group;
use App\Entity\GroupUserConfiguration;
use App\Entity\Payment;
use App\Entity\PaymentCategory;
use App\Entity\User;
use App\Entity\Wallet;
use App\Form\Request\GroupRequest;
use App\Form\Request\PaymentRequest;
use App\Form\Type\GroupType;
use App\Form\Type\PaymentType;
use App\Manager\GroupManager;
use App\Manager\WalletManager;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MobileAppController
 *
 * @package App\Controller\Api
 */
class MobileAppController extends BaseController
{
    /**
     * @Route("/me", methods={"GET"}, defaults={"_format": "json"})
     *
     * @return Response
     */
    public function me() : Response
    {
        return new Response($this->serializer->serialize($this->getUser(), 'json'));
    }

    /**
     * @Route("/member/{group}", methods={"GET"}, defaults={"_format": "json"})
     *
     * @param Group $group
     *
     * @return Response
     */
    public function member(Group $group) : Response
    {
        $groupMember = $this->entityManager->getRepository(User::class)->loadByGroup($group);
        return new Response($this->serializer->serialize($groupMember, 'json'));
    }

    /**
     * @Route("/group", methods={"GET"}, defaults={"_format": "json"})
     *
     * @return Response
     */
    public function group() : Response
    {
        $result = [];
        /** @var User $user */
        $user = $this->getUser();
        $guc = $this->entityManager->getRepository(GroupUserConfiguration::class)->loadByUser($user);

        foreach($guc as $guc_) {
            $result[] = $guc_->getGroup();
        }

        return new Response($this->serializer->serialize($result, 'json'));
    }

    /**
     * @Route("/group/show/{group}", methods={"GET"}, defaults={"_format": "json"})
     *
     * @param Group $group
     *
     * @return Response
     */
    public function getSingleGroup(Group $group): Response
    {
        return new Response($this->serializer->serialize($group, 'json'));
    }

    /**
     * @Route("/group/add", methods={"POST"}, defaults={"_format": "json"})
     *
     * @param Request $request
     * @param GroupManager $groupManager
     *
     * @return Response
     */
    public function postGroup(Request $request, GroupManager $groupManager) : Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $group = new Group();

        try {
            $groupRequest = new GroupRequest($group);
            $form = $this->createForm(GroupType::class, $groupRequest, ['csrf_protection' => false]);

            $form->submit($request->request->all(), false);

            if ($form->isSubmitted() && $form->isValid()) {
                $groupRequest->fillEntity($group);
                $this->getDoctrine()->getManager()->persist($group);

                if ($groupManager->addGroup($group, $user)) {
                    return new Response($this->serializer->serialize($group, 'json'));
                }

                return new Response('Benutzer konnte der Gruppe nicht hinzugefügt werden!', Response::HTTP_BAD_REQUEST);
            }

            return new Response('Ungültige Eingabe', Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/group/edit/{group}", methods={"PATCH"}, defaults={"_format": "json"})
     *
     * @param Request $request
     * @param Group $group
     *
     * @return Response
     */
    public function patchGroup(Request $request, Group $group): Response
    {
        try {
            $groupRequest = new GroupRequest($group);
            $form = $this->createForm(GroupType::class, $groupRequest, ['csrf_protection' => false]);

            $form->submit($request->request->all(), false);

            if ($form->isSubmitted() && $form->isValid()) {
                $groupRequest->fillEntity($group);
                $this->getDoctrine()->getManager()->flush();
                return new Response($this->serializer->serialize($group, 'json'));
            }

            return new Response('Ungültige Eingabe', Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/payment-category", methods={"GET"}, defaults={"_format": "json"})
     * @return Response
     */
    public function paymentCategory() : Response
    {
        $paymentCategories = $this->entityManager->getRepository(PaymentCategory::class)->findAll();
        return new Response($this->serializer->serialize($paymentCategories, 'json'));
    }

    /**
     * @Route("/payment/{wallet}", methods={"GET"}, defaults={"_format": "json"})
     *
     * @param Request $request
     * @param Wallet $wallet
     * @param WalletManager $walletManager
     *
     * @return Response
     * @throws Exception
     */
    public function payment(Request $request, Wallet $wallet, WalletManager $walletManager) : Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$walletManager->hasAccess($user, $wallet)) {
            throw $this->createAccessDeniedException();
        }

        if ($request->query->has('from') && $request->query->has('to')) {
            $fromString = $request->query->get('from');
            $toString = $request->query->get('to');
            $from = DateTime::createFromFormat('Y-m-d H:i:s', $fromString);
            $to   = DateTime::createFromFormat('Y-m-d H:i:s', $toString);
        } else {
            $from = new DateTime('first day of this month');
            $to = new DateTime('last day of this month');
            $from->setTime(0,0,0);
            $to->setTime(23,59,59);
        }

        $payments = $this->entityManager->getRepository(Payment::class)->loadByPeriod($wallet, $from, $to);

        return new Response($this->serializer->serialize($payments, 'json'));
    }

    /**
     * @Route("/payment/show/{payment}", methods={"GET"}, defaults={"_format": "json"})
     *
     * @param Payment $payment
     *
     * @return Response
     */
    public function getSinglePayment(Payment $payment): Response
    {
        $this->denyAccessUnlessGranted('edit', $payment);
        return new Response($this->serializer->serialize($payment, 'json'));
    }

    /**
     * @Route("/payment/add/{wallet}", methods={"POST"}, defaults={"_format": "json"})
     *
     * @param Request $request
     * @param Wallet $wallet
     * @return Response
     */
    public function postPayment(Request $request, Wallet $wallet) : Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $payment = new Payment();
        $payment->setWallet($wallet);
        $payment->setCreator($user);

        $this->denyAccessUnlessGranted('edit', $payment);

        try {
            $paymentRequest = new PaymentRequest($payment);
            $form = $this->createForm(PaymentType::class, $paymentRequest, ['csrf_protection' => false]);

            $form->submit($request->request->all(), false);

            if ($form->isSubmitted() && $form->isValid()) {
                $paymentRequest->fillEntity($payment);
                $this->getDoctrine()->getManager()->persist($payment);
                $this->getDoctrine()->getManager()->flush();
                return new Response($this->serializer->serialize($payment, 'json'));
            }

            return new Response('Ungültige Eingabe', Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/payment/edit/{payment}", methods={"PATCH"}, defaults={"_format": "json"})
     *
     * @param Request $request
     * @param Payment $payment
     * @return Response
     */
    public function patchPayment(Request $request, Payment $payment) : Response
    {
        $this->denyAccessUnlessGranted('edit', $payment);

        try {
            $paymentRequest = new PaymentRequest($payment);
            $form = $this->createForm(PaymentType::class, $paymentRequest, ['csrf_protection' => false]);

            $form->submit($request->request->all(), false);

            if ($form->isSubmitted() && $form->isValid()) {
                $paymentRequest->fillEntity($payment);
                $this->getDoctrine()->getManager()->flush();
                return new Response($this->serializer->serialize($payment, 'json'));
            }

            return new Response('Ungültige Eingabe', Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/payment/remove/{payment}", methods={"DELETE"}, defaults={"_format": "json"})
     *
     * @param Payment $payment
     * @return Response
     */
    public function removePayment(Payment $payment) : Response
    {
        $this->denyAccessUnlessGranted('remove', $payment);
        try {
            $this->getDoctrine()->getManager()->remove($payment);
            $this->getDoctrine()->getManager()->flush();
            return new Response('Ausgabe wurde erfolgreich entfernt!', Response::HTTP_OK);
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }
}