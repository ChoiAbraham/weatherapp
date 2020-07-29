<?php

namespace App\Actions;

use App\Flickr\Flickr;
use App\Form\DTO\CityWeatherDTO;
use App\Form\Type\CityWeatherType;
use App\Responders\Interfaces\ViewResponderInterface;
use App\Responders\RedirectResponder;
use App\Weather\Date;
use App\Weather\Weather;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomepageAction.
 *
 * @Route(
 *     "/",
 *     name="homepage_action",
 * )
 */
final class HomepageAction extends AbstractController
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var Weather */
    private $weatherService;

    /** @var Flickr */
    private $flickrService;

    /** @var Date */
    private $currentDate;

    /**
     * HomepageAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param Weather $weatherService
     * @param Flickr $flickrService
     * @param Date $currentDate
     */
    public function __construct(FormFactoryInterface $formFactory, Weather $weatherService, Flickr $flickrService, Date $currentDate)
    {
        $this->formFactory = $formFactory;
        $this->weatherService = $weatherService;
        $this->flickrService = $flickrService;
        $this->currentDate = $currentDate;
    }

    public function __invoke(Request $request, ViewResponderInterface $responder, RedirectResponder $redirect)
    {
        $cityWeatherType = $this->formFactory->create(CityWeatherType::class)->handleRequest($request);

        $date = $this->currentDate->getCurrentDate('Europe/Paris');

        $weatherData = $this->weatherService->getCurrent('Toulouse');

        $flickrData = $this->flickrService->getRandomPublicPicture();
        if ($cityWeatherType->isSubmitted() && $cityWeatherType->isValid()) {
            /** @var CityWeatherDTO $cityName */
            $cityName = $cityWeatherType->getData();

            $weatherData = $this->weatherService->getCurrent($cityName->getCity());

            if(array_key_exists('error', $weatherData)) {

                return $redirect('api_error_action');
            }
        }

        return $responder(
            'core/home.html.twig',
            [
                'todayDate' => $date,
                'weatherData' => $weatherData,
                'form' => $cityWeatherType->createView(),
            ]
        );
    }
}