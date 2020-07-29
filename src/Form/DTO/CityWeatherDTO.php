<?php


namespace App\Form\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class CityWeatherDTO
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="20")
     */
    protected $city;

    /**
     * CityWeatherDTO constructor.
     */
    public function __construct(?string $city = '')
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }
}