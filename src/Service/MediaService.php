<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;

class MediaService
{
    private array $config;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->config = $parameterBag->get('media');
    }

    public function upload(File $file): string
    {
        $generatedName = $this->generateName($file->guessExtension());
        $file->move($this->getRepository(), $generatedName);
        return $generatedName;
    }

    public function getRepository($absolute = true): string
    {
        if($absolute){
            return $this->config['repository'];
        }

        return 'public/data';
    }

    private function generateName($extension): string
    {
        return sprintf('%s.%s', md5(uniqid("file_", true)), $extension);
    }
}