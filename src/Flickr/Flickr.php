<?php

namespace App\Flickr;

use JMS\Serializer\SerializerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class Flickr
{
    /** @var FlickrGuzzleClient */
    private $client;

    /** @var SerializerInterface */
    private $serializer;

    private $apiKey;

    /** @var LoggerInterface */
    private $logger;

    /** @var ContainerInterface */
    private $container;

    /**
     * Flickr constructor.
     * @param FlickrGuzzleClient $client
     * @param SerializerInterface $serializer
     * @param $apiKey
     * @param LoggerInterface $logger
     * @param ContainerInterface $container
     */
    public function __construct(FlickrGuzzleClient $client, SerializerInterface $serializer, $apiKey, LoggerInterface $logger, ContainerInterface $container)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->apiKey = $apiKey;
        $this->logger = $logger;
        $this->container = $container;
    }

    // get random public pictures from flickr, filter by tag/input
    // Authentification not required
    public function getRandomPublicPicture($input = 'Toulouse weather')
    {
        $tag = $this->getTag($input);

        $uri = 'https://www.flickr.com/services/rest/?method=flickr.photos.search';
        $uri .= '&api_key=ad5c81571deaae41d0b48ae70a66894a';
        $uri .= '&tags=' . $tag;
        $uri .= '&extras=url_l';
        $uri .= '&per_page=20&page=1';
        $uri .= '&format=json&nojsoncallback=1';

        try {
            $response = $this->client->get($uri, ['verify' => false]);
        } catch (\Exception $e) {
            $this->logger->error('The flickr API returned an error: '.$e->getMessage());
            return ['error' => 'Les informations ne sont pas disponibles pour le moment.'];
        }

        $content = $response->getBody()->getContents();
        $trimmed = $this->remove_utf8($content);

        $data = $this->serializer->deserialize($trimmed, 'array', 'json');
        if (empty($data['photos']['photo'])) {
            return false;
        }

        $rand = random_int(1, 10);

        $id = $data['photos']['photo'][$rand]['id'];
        $owner = $data['photos']['photo'][$rand]['owner'];
        $secret = $data['photos']['photo'][$rand]['secret'];
        $server =$data['photos']['photo'][$rand]['server'];
        $farm = $data['photos']['photo'][$rand]['farm'];
        $title = $data['photos']['photo'][$rand]['title'];
        $isPublic = $data['photos']['photo'][$rand]['ispublic'];
        $isFriend = $data['photos']['photo'][$rand]['isfriend'];
        $isFamily = $data['photos']['photo'][$rand]['isfamily'];

        $urlPicture = 'http://farm' . $farm . '.staticflickr.com/' . $server . '/' . $id . '_' . $secret . '.jpg';
        dd($urlPicture);

        return $urlPicture;
    }

    // Get professionnal random pictures from Flickr
    // Authentification needed
    // TODO : Flickr API Authentification via OAuth
    public function getProfessionnalPictureFromFlickr() {
    }

    public function remove_utf8($text){
        $search = array('jsonFlickrApi(',')');
        return str_replace($search, '', $text);
    }

    public function getTag($tag) {
        return str_replace(' ', '+', trim($tag));
    }
}