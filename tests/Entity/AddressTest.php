<?php
namespace App\Tests\Entity;

use App\Entity\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    /**
     * @dataProvider getSampleAddresses
     */
    public function testToString(string $street, string $zipcode, string $city, string $country): void
    {
        $address = $this->generateEntity($street, $zipcode, $city, $country);

        $expectedString = sprintf('%s - %s %s', $street, $zipcode, $city);
        $this->assertEquals($expectedString, (string) $address);
    }

    private function generateEntity(string $street, string $zipcode, string $city, string $country): Address
    {
        $address = new Address();
        $address->setStreet($street);
        $address->setZipcode($zipcode);
        $address->setCity($city);
        $address->setCountry($country);

        return $address;
    }

    public function getSampleAddresses(): array
    {
        return [
            ['124 rue de Rivoli', '75000', 'Paris', 'FR'],
            ['12 rue du Pont', '35000', 'Rennes', 'FR'],
            ['124 avenue Charles de Gaules', '59000', 'Lille', 'FR'],
            ['130 place de la Concorde', '75000', 'Paris', 'FR'],
        ];
    }
}
