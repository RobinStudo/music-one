<?php
namespace App\Service;

use App\Entity\Address;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LocationService
{
    private HttpClientInterface $httpClient;
    private array $config;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->httpClient = $httpClient;
        $this->config = $parameterBag->get('location');
    }

    public function checkAddress(Address $address): bool
    {
        $endpoint = sprintf("mapbox.places/%s.json", $address->getZipcode());
        $results = $this->call($endpoint, [
            'country' => $address->getCountry(),
            'types' => 'postcode',
        ]);

        if(empty($results)){
            return false;
        }

        $result = array_shift($results);
        foreach($result['context'] as $context){
            if($context['text'] == $address->getCity()){
                return true;
            }
        }

        return false;
    }

    private function call(string $endpoint, array $data = [], string $method = "GET")
    {
        $url = sprintf("%s%s", $this->config['url'], $endpoint);
        $query = [
            'access_token' => $this->config['token'],
        ];
        if($method === 'GET'){
            $query = array_merge($query, $data);
        }

        $response = $this->httpClient->request($method, $url, [
            'query' => $query
        ]);

        return $response->toArray()['features'];
    }
}