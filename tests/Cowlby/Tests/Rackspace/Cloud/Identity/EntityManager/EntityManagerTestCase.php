<?php

namespace Cowlby\Tests\Rackspace\Cloud\Identity\EntityManager;

use Guzzle\Http\Client;
use Guzzle\Http\Plugin\MockPlugin;
use Cowlby\Tests\Rackspace\Cloud\Identity\TestCase;
use Cowlby\Rackspace\Common\Http\GuzzleClientAdapter;

class EntityManagerTestCase extends TestCase
{
    protected $client;

    protected $mockPlugin;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $guzzle = new Client();
        $this->mockPlugin = new MockPlugin();
        $guzzle->addSubscriber($this->mockPlugin);
        $this->client = new GuzzleClientAdapter($guzzle);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->client = NULL;
        $this->mockPlugin = NULL;
    }
}
