<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\Type\CheckoutSessionType;
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

    /**
     * @Route("", name="main")
     */
    public function main(Request $request): Response
    {
        if($request->query->has('event')){
            $event = $this->eventRepository->find($request->query->get('event'));
            if($event){
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

    public function cart($request, $session): Response
    {
        $form = $this->createForm(CheckoutSessionType::class, $session);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

        }

        return $this->render('checkout/cart.html.twig', [
            'form' => $form->createView(),
            'session' => $session
        ]);
    }

    public function account($request, $session): Response
    {

    }

    public function payment($request, $session): Response
    {

    }

    public function finish($request, $session): Response
    {

    }
}
