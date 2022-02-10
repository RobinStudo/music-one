<?php

namespace App\Controller;

use App\Entity\Event;

use App\Form\Type\CheckoutSessionType;
use App\Form\Type\UserType;
use App\Model\CheckoutSession;
use App\Service\CheckoutService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checkout", name="checkout_")
 */
class CheckoutController extends AbstractController
{
    private EntityManagerInterface $em;
    private $eventRepository;
    private CheckoutService $checkoutService;

    public function __construct(EntityManagerInterface $em, CheckoutService $checkoutService)
    {
        $this->em = $em;
        $this->eventRepository = $this->em->getRepository(Event::class);
        $this->checkoutService = $checkoutService;
    }

    // TODO - Gérer l'utilisateur non connecté
    // TODO - Gérer la disponiblité de l'événement
    // TODO - Améliorer la selection du nombre de place
    // TODO - Usage multiple du paiement
    // TDOD - L'utilisateur à déjà une réservation

    /**
     * @Route("", name="main")
     */
    public function main(Request $request): Response
    {
            if ($request->query->has('event')) {
                $event = $this->eventRepository->find($request->query->get('event'));
                if ($event) {
                    $this->checkoutService->initSession($event);
                    return $this->redirectToRoute('checkout_main');
                }
            }

        if($session = $this->checkoutService->retrieveSession()){
            switch($session->getStatus()){
                case CheckoutSession::STATUS_ACCOUNT:
                    $method = 'account';
                    break;
                case CheckoutSession::STATUS_PAYMENT:
                    $method = 'payment';
                    break;
                case CheckoutSession::STATUS_FINISH:
                    $method = 'finish';
                    break;
                default:
                    $method = 'cart';
            }

            $controller = sprintf('%s::%s', self::class, $method);
            return $this->forward($controller, [
                'request' => $request,
                'session' => $session
            ]);
        }

        return $this->redirectToRoute('main_index');
    }


    public function cart(Request $request, CheckoutSession $session): Response
    {
        if ($this->isGranted('ROLE_USER') == false) {
            return $this->redirectToRoute('security_login');
        } else {
            $form = $this->createForm(CheckoutSessionType::class, $session);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $session->setStatus(CheckoutSession::STATUS_ACCOUNT);
                return $this->redirectToRoute('checkout_main');
            }

            return $this->render('checkout/cart.html.twig', [
                'form' => $form->createView(),
                'session' => $session
            ]);
        }
    }

    public function account(Request $request, CheckoutSession $session): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user, [
            'mode' => UserType::MODE_CHECKOUT,
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $session->setStatus(CheckoutSession::STATUS_PAYMENT);
            return $this->redirectToRoute('checkout_main');
        }

        return $this->render('checkout/account.html.twig', [
            'form' => $form->createView(),
            'session' => $session
        ]);
    }

    public function payment(Request $request, CheckoutSession $session): Response
    {
        if($paymentId = $request->query->get('payment_intent')){
            if($booking = $this->checkoutService->finalize($session, $paymentId)){
                $this->em->persist($booking);
                $this->em->flush();
                $session->setStatus(CheckoutSession::STATUS_FINISH);
                return $this->redirectToRoute('checkout_main');
            }
        }

        $payment = $this->checkoutService->preparePayment($session);

        return $this->render('checkout/payment.html.twig', [
            'session' => $session,
            'payment' => $payment,
        ]);
    }

    public function finish(Request $request, CheckoutSession $session): Response
    {
        return $this->render('checkout/finish.html.twig', [
            'session' => $session
        ]);
    }
}
