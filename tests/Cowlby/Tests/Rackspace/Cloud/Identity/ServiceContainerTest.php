<?php

namespace Cowlby\Tests\Rackspace\Cloud\Identity;

use Cowlby\Rackspace\Cloud\Identity\ServiceContainer;

class ServiceContainerTest extends TestCase
{
    private $container;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $credentials = $this->getMock('Cowlby\\Rackspace\\Cloud\\Identity\\Credentials\\CredentialsInterface');
        $client = $this->getMock('Guzzle\\Http\\Client');

        $this->container = new ServiceContainer($credentials);
        $this->container['client'] = $client;
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->container = NULL;
    }

    public function testGetAuthManager()
    {
        $expected = 'Cowlby\\Rackspace\\Cloud\\Identity\\EntityManager\\AuthManagerInterface';
        $actual = $this->container->getAuthManager();

        $this->assertInstanceOf($expected, $actual);
    }
}
