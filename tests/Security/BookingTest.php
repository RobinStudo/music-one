<?php
namespace App\Tests\Security;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingTest extends WebTestCase
{
    public function testBookingButtonDeactivation(): void
    {
        $client = static::createClient();

        $container = static::getContainer();
        $eventRepository = $container->get(EventRepository::class);
        $event = $eventRepository->findOneBy([]);

        $client->loginUser($event->getOwner());

        $url = sprintf('/event/%s', $event->getId());
        $client->request('GET', $url);

        $this->assertSelectorTextNotContains(
            '.event-view__actions',
            'Réserver'
        );
    }
}
