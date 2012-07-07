<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

class User
{
	protected $id;
	
	protected $enabled;
	
	protected $username;
	
	protected $updated;
	
	protected $created;
	
	public function __construct()
	{
	}
	
	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getEnabled()
	{
		return $this->enabled;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdated()
	{
		return $this->updated;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * @param string $id
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @param string $enabled
	 */
	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;
		return $this;
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}

	/**
	 * @param \DateTime $updated
	 */
	public function setUpdated(\DateTime $updated)
	{
		$this->updated = $updated;
		return $this;
	}

	/**
	 * @param \DateTime $created
	 */
	public function setCreated(\DateTime $created)
	{
		$this->created = $created;
		return $this;
	}
}
