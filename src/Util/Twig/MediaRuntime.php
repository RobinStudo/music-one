<?php
namespace App\Util\Twig;

use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\RuntimeExtensionInterface;

class MediaRuntime implements RuntimeExtensionInterface
{
    private array $config;
    private Packages $assetManager;

    public function __construct(ParameterBagInterface $parameterBag, Packages $assetManager)
    {
        $this->config = $parameterBag->get('media');
        $this->assetManager = $assetManager;
    }

    public function buildPath(string $media): string
    {
        if(preg_match('/https?:\/\//', $media)){
            return $media;
        }

        $path = sprintf('%s%s', $this->config['path'], $media);
        return $this->assetManager->getUrl($path);
    }
}