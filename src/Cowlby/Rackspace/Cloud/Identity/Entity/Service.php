<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

class Service
{
    protected $name;

    protected $type;

    protected $endpoints;

    public function __construct()
    {
        $this->endpoints = array();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getEndpoints()
    {
        return $this->endpoints;
    }

    public function getEndpoint($pos = 0)
    {
        return $this->endpoints[$pos];
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param Endpoint $endpoint The Endpoint to add.
     * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Service
     */
    public function addEndpoint(Endpoint $endpoint)
    {
        $this->endpoints[] = $endpoint;
        return $this;
    }
}
