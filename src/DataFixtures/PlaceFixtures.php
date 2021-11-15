<?php
namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Place;

class PlaceFixtures extends AbstractFixtures
{
    protected function getType(): string
    {
        return Place::class;
    }

    protected function getFile(): string
    {
        return 'place';
    }

    protected function hookAddress($entity, $data)
    {
        $address = new Address();
        $address->setStreet($data['street']);
        $address->setZipcode($data['zipcode']);
        $address->setCity($data['city']);
        $address->setCountry($data['country']);

        $entity->setAddress($address);
    }

    protected function getReferenceKey($entity): string
    {
        return $entity->getLabel();
    }
}
