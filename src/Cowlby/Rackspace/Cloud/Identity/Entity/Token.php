<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

class Token
{
    protected $id;

    protected $expires;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getExpires()
    {
        return $this->expires;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setExpires(\DateTime $expires)
    {
        $this->expires = $expires;
        return $this;
    }

    public function isValid()
    {
    	$now = new \DateTime();
    	return $now < $this->expires;
    }
}
