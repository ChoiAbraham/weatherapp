<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractWebTestCase extends WebTestCase
{
    /** @var KernelBrowser */
    protected $client;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ContainerInterface */
    protected $containerService;

    protected function setUp()
    {
        $this->client = self::createClient();

        $this->containerService = self::$container;
    }
}
