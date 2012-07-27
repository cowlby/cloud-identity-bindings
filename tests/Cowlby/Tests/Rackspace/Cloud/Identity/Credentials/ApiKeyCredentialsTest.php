<?php

namespace Cowlby\Tests\Rackspace\Cloud\Identity\Credentials;

use Cowlby\Tests\Rackspace\Cloud\Identity\TestCase;
use Cowlby\Rackspace\Cloud\Identity\Credentials\ApiKeyCredentials;

class ApiKeyCredentialsTest extends TestCase
{
    const USERNAME = 'hub_cap';
    const API_KEY = 'a86850deb2742ec3cb41518e26aa2d89';

    /**
     * @var Cowlby\Rackspace\Cloud\Identity\Credentials\ApiKeyCredentials
     */
    private $credentials;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->credentials = new ApiKeyCredentials(self::USERNAME, self::API_KEY);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->credentials = NULL;
    }

    public function testGetPayloadWorks()
    {
        $expected = '{"credentials":{"username":"hub_cap","key":"a86850deb2742ec3cb41518e26aa2d89"}}';
        $this->assertEquals($expected, $this->credentials->getPayload());
    }
}
