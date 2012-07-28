<?php

namespace Cowlby\Tests\Rackspace\Cloud\Identity\EntityManager;

use Guzzle\Http\Message\Response;
use Cowlby\Rackspace\Common\Cache\NullCacheAdapter;
use Cowlby\Rackspace\Cloud\Identity\EntityManager\AuthManager;

class AuthManagerTest extends EntityManagerTestCase
{
    private $em;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $credentials = $this->getMock('\\Cowlby\\Rackspace\\Cloud\\Identity\\Credentials\\CredentialsInterface');
        $cache = new NullCacheAdapter();

        $this->em = new AuthManager($this->client, $credentials, $cache);
    }

    public function testGetAuth()
    {
        $message = <<<HTTP
HTTP/1.1 200 OK
Server: nginx/0.8.55
Date: Fri, 27 Jul 2012 20:39:44 GMT
Content-Type: application/json
Transfer-Encoding: chunked
Connection: keep-alive
vary: Accept, Accept-Encoding
response-source: cloud-auth
Content-Encoding: gzip
Front-End-Https: on

{"auth":{"token":{"id":"a6a07901-17e4-40a5-a630-5310722f2c09","expires":"2012-07-28T07:38:22.000-05:00"},"serviceCatalog":{"cloudServersOpenStack":[{"region":"DFW","publicURL":"https:\/\/dfw.servers.api.rackspacecloud.com\/v2\/123456"}],"cloudFilesCDN":[{"region":"DFW","publicURL":"https:\/\/cdn.clouddrive.com\/v1\/MossoCloudFS_37f5a8f6-3d24-48d3-8803-328876313f6a","v1Default":true}],"cloudDNS":[{"publicURL":"https:\/\/dns.api.rackspacecloud.com\/v1.0\/123456"}],"cloudFiles":[{"region":"DFW","publicURL":"https:\/\/storage101.dfw1.clouddrive.com\/v1\/MossoCloudFS_37f5a8f6-3d74-ffff-9803-328876313f6a","v1Default":true,"internalURL":"https:\/\/snet-storage101.dfw1.clouddrive.com\/v1\/MossoCloudFS_37f5a8f6-3d74-ffff-9803-328876313f6a"}],"cloudMonitoring":[{"publicURL":"https:\/\/monitoring.api.rackspacecloud.com\/v1.0\/123456"}],"cloudLoadBalancers":[{"region":"ORD","publicURL":"https:\/\/ord.loadbalancers.api.rackspacecloud.com\/v1.0\/123456"},{"region":"DFW","publicURL":"https:\/\/dfw.loadbalancers.api.rackspacecloud.com\/v1.0\/123456"}],"cloudDatabases":[{"region":"ORD","publicURL":"https:\/\/ord.databases.api.rackspacecloud.com\/v1.0\/123456"},{"region":"DFW","publicURL":"https:\/\/dfw.databases.api.rackspacecloud.com\/v1.0\/123456"}],"cloudServers":[{"publicURL":"https:\/\/servers.api.rackspacecloud.com\/v1.0\/123456","v1Default":true}]}}}
HTTP;
        $this->mockPlugin->addResponse(Response::fromMessage($message));

        $auth = $this->em->authenticate();

        $this->assertInstanceOf('Cowlby\\Rackspace\\Cloud\\Identity\\Entity\\Auth', $auth);
        $this->assertInstanceOf('Cowlby\\Rackspace\\Cloud\\Identity\\Entity\\Token', $auth->getToken());
        $this->assertInstanceOf('Cowlby\\Rackspace\\Cloud\\Identity\\Entity\\ServiceCatalog', $auth->getServiceCatalog());

        $token = $auth->getToken();
        $dt = new \DateTime('2012-07-28T07:38:22.000-05:00');
        $this->assertEquals('a6a07901-17e4-40a5-a630-5310722f2c09', $token->getId());
        $this->assertEquals($dt, $token->getExpires());

        $catalog = $auth->getServiceCatalog();
        $services = $catalog->getServices();

        $this->assertArrayHasKey('cloudDNS', $services);
        foreach ($services as $service) {
            $this->assertInstanceOf('Cowlby\\Rackspace\\Cloud\\Identity\\Entity\\Service', $service);
        }
    }
}
