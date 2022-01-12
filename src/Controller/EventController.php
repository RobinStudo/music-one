<?php
namespace App\Controller;

use App\Entity\Event;
use App\Form\Type\EventType;
use App\Repository\EventRepository;
use App\Service\MediaService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event", name="event_")
 */
class EventController extends AbstractController
{
    private EntityManagerInterface $em;
    private EventRepository $eventRepository;
    private MediaService $mediaService;

    public function __construct(EntityManagerInterface $em, EventRepository $eventRepository, MediaService $mediaService)
    {
        $this->em = $em;
        $this->eventRepository = $eventRepository;
        $this->mediaService = $mediaService;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $events = $this->eventRepository->findAll();

        return $this->render('event/index.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/{id}", name="show", requirements={"id"="\d+"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/new", name="new")
     * @Route("/{id}/edit", name="edit")
     * @IsGranted("EVENT_MODIFY", subject="event")
     */
    public function form(Request $request, Event $event = null): Response
    {
        $isNew = !$event;
        if(!$event){
            $event = new Event();
        }
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($event->getPictureUrl()){
                $event->setPicture($event->getPictureUrl());
            }else if($event->getPictureFile()){
                // Upload
            }

            $event->setOwner($this->getUser());
            $this->em->persist($event);
            $this->em->flush();

            $action = $isNew ? 'créé' : 'modifié';
            $message = sprintf('Votre événement a bien été %s', $action);
            $this->addFlash('notice', $message);
            return $this->redirectToRoute('event_show', [
                'id' => $event->getId(),
            ]);
        }

        return $this->render('event/form.html.twig', [
            'form' => $form->createView(),
            'isNew' => $isNew,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     * @IsGranted("EVENT_MODIFY", subject="event")
     */
    public function delete(Event $event): Response
    {
        // TODO - Gérer l'annulation des réservations
        $this->em->remove($event);
        $this->em->flush();

        $message = sprintf('Votre événement "%s" a bien été supprimé', $event->getName());
        $this->addFlash('notice', $message);
        return $this->redirectToRoute('event_index');
    }
}
