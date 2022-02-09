<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*
 * @Routes("/checkout", name"checkout_")
 */
class CheckoutController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->eventRepository = $this->em->getRepository(Event::class);
    }
    /**
     * @Route("", name="main")
     */
    public function main(Request $request): Response
    {
/*        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
        ]);*/
        if(!$request->query->has('event')) {

        }
        return $this->redirectToRoute('main_index');
    }
}
