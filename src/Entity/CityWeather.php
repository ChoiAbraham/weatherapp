<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CityWeather.
 *
 * @ORM\Table(name="cityweather_entity")
 * @ORM\Entity(repositoryClass="App\Repository\CityWeatherRepository")
 */
class CityWeather
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $city;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * CityWeather constructor.
     */
    public function __construct($city = '')
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }
}
