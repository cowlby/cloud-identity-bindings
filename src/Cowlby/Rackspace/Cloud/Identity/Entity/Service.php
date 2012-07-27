<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class Service extends AbstractEntity
{
    protected $name;

    protected $endpoints;

    public function __construct()
    {
        $this->endpoints = array();
    }

    public function denormalize(DenormalizerInterface $denormalizer, $data, $format = NULL)
    {
        for ($i = 0, $c = count($data); $i < $c; $i++) {
            $this->addEndpoint($denormalizer->denormalize($data[$i], __NAMESPACE__ . '\\Endpoint', $format));
            unset($data[$i]);
        }

        parent::denormalize($denormalizer, $data, $format);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param Endpoint $endpoint The Endpoint to add.
     * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Service
     */
    public function addEndpoint(Endpoint $endpoint)
    {
        $this->endpoints[] = $endpoint;
        return $this;
    }
}
