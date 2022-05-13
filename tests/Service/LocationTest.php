<?php
namespace App\Tests\Service;

use App\Entity\Address;
use App\Service\LocationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LocationTest extends KernelTestCase
{
    /**
     * @dataProvider getCities
     */
    public function testCityChecker(string $zipcode, string $city, bool $expected): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $locationService = $container->get(LocationService::class);

        $address = $this->getAddressEntity($zipcode, $city);
        $result = $locationService->checkAddress($address);

        $this->assertEquals($expected, $result);
    }

    private function getAddressEntity(string $zipcode, string $city): Address
    {
        $address = new Address();
        $address->setZipcode($zipcode);
        $address->setCity($city);
        $address->setCountry('FR');

        return $address;
    }

    public function getCities(): array
    {
        return [
            ['35000', 'Rennes', true],
            ['59000', 'Lille', true],
            ['13001', 'Marseille', true],
            ['23430', 'Niort', false],
            ['12000', 'Paris', false],
            ['35460', 'Lens', false],
        ];
    }

}
