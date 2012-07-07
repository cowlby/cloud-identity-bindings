<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

class Endpoint
{
	protected $region;
	
	protected $tenantId;
	
	protected $publicURL;
	
	public function __construct()
	{
	}
	
	/**
	 * @return string
	 */
	public function getRegion()
	{
		return $this->region;
	}
	
	/**
	 * @return string
	 */
	public function getTenantId()
	{
		return $this->tenantId;
	}

	/**
	 * @return string
	 */
	public function getPublicURL()
	{
		return $this->publicURL;
	}
	
	/**
	 * @param string $region
	 * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Endpoint
	 */
	public function setRegion($region)
	{
		$this->region = $region;
		return $this;
	}
	
	/**
	 * @param string $tenantId
	 * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Endpoint
	 */
	public function setTenantId($tenantId)
	{
		$this->tenantId = $tenantId;
		return $this;
	}

	/**
	 * @param string $publicURL
	 * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Endpoint
	 */
	public function setPublicURL($publicURL)
	{
		$this->publicURL = $publicURL;
		return $this;
	}

}
