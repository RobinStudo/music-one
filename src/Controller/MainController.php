<?php
namespace App\Controller;

use App\Form\Type\ContactType;
use App\Model\Contact;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventRepository;
use DateTime;

/**
 * @Route("", name="main_")
 */
class MainController extends AbstractController
{

    private EntityManagerInterface $em;
    private EventRepository $eventRepository;

    public function __construct(EntityManagerInterface $em, EventRepository $eventRepository)
    {
        $this->em = $em;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {

        // evenement mis en avant : 
        $events = $this->eventRepository->findAll();
        $mainEvent = null;
        $isEvent = (count($events) > 0);
        if ($isEvent){
            $mainEvent = $events[rand(0, count($events) -1)];
        }
        
        // prochain event où il y a de la place : 
        $nextEvent = null;
        if ($isEvent){
            $startAt = array();
            foreach ($events as $key => $row)
            {
                $startAt[$key] = $row->getStartAt();;
            }
            array_multisort($startAt, SORT_ASC, $events);
            
            $find = false;
            foreach($events as $event){
                if (!$find){
                    $capacity = $event->getCapacity();
                    $participants = count($event->getParticipants());
                    $isPlace = ($capacity - $participants) > 0;
                    $now = date('l jS \of F Y h:i:s A');
                    $isDate = (new DateTime() < $event->getStartAt());
                    if ($isPlace && $isDate){
                        $nextEvent = $event;
                        $find = true;
                    }
                }
            }
        }

        return $this->render('main/index.html.twig', [
            'mainEvent' => $mainEvent, 
            'nextEvent' => $nextEvent,
            'isEvent' => $isEvent
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerService $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($mailer->sendSupport($contact)) {
                $this->addFlash('notice', 'Message envoyé');
                return $this->redirectToRoute('main_contact');
            }
            
            $this->addFlash('error', 'Echec de l\'envoi');
        }

        return $this->render('main/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
