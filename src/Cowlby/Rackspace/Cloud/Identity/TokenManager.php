<?php

namespace Cowlby\Rackspace\Cloud\Identity;

use Cowlby\Rackspace\Cloud\Common\Cache\CacheAdapterInterface;

use Cowlby\Rackspace\Cloud\Common\HydratorInterface;
use Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface;
use Cowlby\Rackspace\Cloud\Identity\Entity;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\BadResponseException;

class TokenManager
{
    protected $client;

    protected $credentials;

    protected $hydrator;

    protected $cache;

    protected $token;

    protected $serviceCatalog;

    public function __construct(CredentialsInterface $credentials, GuzzleClient $client, HydratorInterface $hydrator, CacheAdapterInterface $cache)
    {
        $this->setCredentials($credentials);
        $this->setClient($client);
        $this->setHydrator($hydrator);
        $this->setCache($cache);
    }

    public function setCredentials(CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    public function setClient(GuzzleClient $client)
    {
        $this->client = $client;
        return $this;
    }

    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    public function setCache(CacheAdapterInterface $cache)
    {
    	$this->cache = $cache;
    	return $this;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getToken()
    {
    	$token = $this->cache->fetch($this->getTokenCacheId());

        if ($token === FALSE) {
            $this->authenticate();
        } else {
        	$this->token = unserialize($token);
        }

        return $this->token;
    }

    public function getTokenCacheId()
    {
    	return sprintf('[%s][%s]', md5($this->credentials->getPayload()), 'token');
    }

    public function getServiceCatalog()
    {
    	$serviceCatalog = $this->cache->fetch($this->getServiceCatalogCacheId());

        if ($serviceCatalog === FALSE) {
            $this->authenticate();
        } else {
        	$this->serviceCatalog = unserialize($serviceCatalog);
        }

        return $this->serviceCatalog;
    }

    public function getServiceCatalogCacheId()
    {
    	return sprintf('[%s][%s]', md5($this->credentials->getPayload()), 'serviceCatalog');
    }

    protected function authenticate()
    {
        $request = $this->client->post('tokens', NULL, $this->credentials->getPayload());
        $response = $request->send();

        $json = json_decode($response->getBody(), TRUE);

        $this->token = new Entity\Token();
        $this->hydrator->hydrateEntity($this->token, $json['access']['token']);

        $this->serviceCatalog = new Entity\ServiceCatalog();
        foreach ($json['access']['serviceCatalog'] as $jsonService) {

            $service = new Entity\Service();
            $this->hydrator->hydrateEntity($service, $jsonService);
            $this->serviceCatalog->addService($service);

            foreach ($jsonService['endpoints'] as $jsonEndpoint) {

                $endpoint = new Entity\Endpoint();
                $this->hydrator->hydrateEntity($endpoint, $jsonEndpoint);
                $service->addEndpoint($endpoint);
            }
        }

        $this->cache->save($this->getTokenCacheId(), serialize($this->token));
        $this->cache->save($this->getServiceCatalogCacheId(), serialize($this->serviceCatalog));

        return $this;
    }
}
