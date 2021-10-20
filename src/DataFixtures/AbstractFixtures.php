<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractFixtures extends Fixture
{
    private $config;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->config = $parameterBag->get('fixture');
    }

    public function load(ObjectManager $manager): void
    {
        $type = $this->getType();

        $path = sprintf('%s/%s.yml', $this->config['path'], $this->getFile());
        $raw = Yaml::parseFile($path);
        foreach($raw['data'] as $data){
            $entity = new $type;

            foreach($data as $key => $value){
                $setter = sprintf('set%s', ucfirst($key));
                $entity->$setter($value);
            }

            $manager->persist($entity);
        }

        $manager->flush();
    }

    protected abstract function getType(): string;
    protected abstract function getFile(): string;
}