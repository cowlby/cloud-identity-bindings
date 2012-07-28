<?php

namespace Cowlby\Tests\Rackspace\Cloud\Identity\EntityManager;

use Cowlby\Rackspace\Cloud\Identity\Http\GuzzleClientAdapter;
use Cowlby\Tests\Rackspace\Cloud\Identity\TestCase;
use Guzzle\Http\Client;
use Guzzle\Http\Plugin\MockPlugin;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;

class EntityManagerTestCase extends TestCase
{
    protected $client;

    protected $mockPlugin;

    protected $serializer;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->serializer = new Serializer(
            array(new CustomNormalizer()),
            array('json' => new JsonEncoder())
        );

        $guzzle = new Client();
        $this->mockPlugin = new MockPlugin();
        $guzzle->addSubscriber($this->mockPlugin);
        $this->client = new GuzzleClientAdapter($guzzle, $this->serializer);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->client = NULL;
        $this->mockPlugin = NULL;
        $this->serializer = NULL;
    }
}
