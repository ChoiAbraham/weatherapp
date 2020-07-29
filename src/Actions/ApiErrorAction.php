<?php

namespace App\Actions;

use App\Form\Type\CityWeatherType;
use App\Responders\Interfaces\ViewResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiErrorAction.
 *
 * @Route("/error", name="api_error_action")
 */
final class ApiErrorAction
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /**
     * ApiErrorAction constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }


    public function __invoke(Request $request, ViewResponderInterface $responder)
    {
        $cityWeatherType = $this->formFactory->create(CityWeatherType::class)->handleRequest($request);

        $errorMessage = 'Les informations ne sont pas disponibles pour le moment.';

        return $responder(
            'error/error.html.twig',
            [
                'errorMessage' => $errorMessage,
                'form' => $cityWeatherType->createView(),
            ]
        );
    }
}
