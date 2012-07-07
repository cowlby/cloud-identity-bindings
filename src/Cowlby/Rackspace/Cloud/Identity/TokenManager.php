<?php

namespace Cowlby\Rackspace\Cloud\Identity;

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
	
	protected $token;
	
	protected $serviceCatalog;
	
	public function __construct(CredentialsInterface $credentials, GuzzleClient $client, HydratorInterface $hydrator)
	{
		$this->setCredentials($credentials);
		$this->setClient($client);
		$this->setHydrator($hydrator);
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
		if ($this->token === NULL) {
			$this->authenticate();
		}
		
		return $this->token;
	}
	
	public function getServiceCatalog()
	{
		if ($this->serviceCatalog === NULL) {
			$this->authenticate();
		}
		
		return $this->serviceCatalog;
	}
	
	protected function authenticate()
	{
		$request = $this->client->post('tokens');
		$request->setBody($this->credentials->getPayload());
		
		try {
			$response = $request->send();
		} catch (BadResponseException $e) {
			throw $e;
		}
		
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
		
		return $this;
	}
}
