<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Topic;
use App\Repository\TopicRepository;

/**
 * @Route("topic", name="topic_")
 */
class TopicController extends AbstractController
{

    private TopicRepository $eventRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $topics = $this->topicRepository->findAll();

        return $this->render('forum/index.html.twig', [
            'topics' => $topics
        ]);
    }
}
