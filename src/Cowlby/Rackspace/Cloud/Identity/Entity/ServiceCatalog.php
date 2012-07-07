<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

class ServiceCatalog
{
	protected $services;
	
	public function __construct()
	{
		$this->services = array();
	}
	
	public function getServices()
	{
		return $this->services;
	}
	
	public function getService($name)
	{
		return $this->services[$name];
	}
	
	public function addService(Service $service)
	{
		$this->services[$service->getName()] = $service;
	}
	
	public function getCloudDnsService()
	{
		return $this->services['cloudDNS'];
	}
}
