<?php

namespace App\Weather;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

class Weather
{
    private $weatherClient;
    private $serializer;
    private $apiKey;
    private $logger;

    /** @var TemperatureConversion */
    private $temperatureToCelsius;

    public function __construct(TemperatureConversion $temperatureToCelsius, Client $weatherClient, SerializerInterface $serializer, LoggerInterface $logger, $apiKey)
    {
        $this->weatherClient = $weatherClient;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->apiKey = $apiKey;
        $this->temperatureToCelsius = $temperatureToCelsius;
    }

    public function getCurrent($city)
    {
        $uri = '/data/2.5/weather?q=' . $city . '&APPID='.$this->apiKey;

        try {
            $response = $this->weatherClient->get($uri);
        } catch (\Exception $e) {
            $this->logger->error('The weather API returned an error: '.$e->getMessage());
            return ['error' => 'Les informations ne sont pas disponibles pour le moment.'];
        }

        $data = $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');

        //Temperature To Celsius
        $temp = $this->temperatureToCelsius->kelvin_to_celsius($data['main']['temp']);
        $minTemp = $this->temperatureToCelsius->kelvin_to_celsius($data['main']['temp_min']);
        $maxTemp = $this->temperatureToCelsius->kelvin_to_celsius($data['main']['temp_max']);

        //Description to French
        $descriptionInFrench = $this->getDescriptionInFrench($data['weather'][0]['main']);

        return [
            'city' => $data['name'],
            'description' => $descriptionInFrench,
            'temperature' => $temp,
            'minTemp' => $minTemp,
            'maxTemp' => $maxTemp
        ];
    }

    public function getDescriptionInFrench($description)
    {
        switch ($description) {
            case 'Clouds':
                $description = "Nuageux";
                break;
            case 'Rain':
                $description = "Pluie";
                break;
            case 'Snow':
                $description = "Neige";
                break;
            case 'Clear':
                $description = "Clair";
                break;
            case 'Mist':
                $description = "Brume";
                break;
        }

        return $description;
    }
}