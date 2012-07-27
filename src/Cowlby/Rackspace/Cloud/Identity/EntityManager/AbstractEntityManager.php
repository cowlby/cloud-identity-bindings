<?php

namespace Cowlby\Rackspace\Cloud\Identity\EntityManager;

use Guzzle\Http\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface;

abstract class AbstractEntityManager
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Constructor.
     *
     * @param ClientInterface $client
     * @param SerializerInterface $serializer
     */
    public function __construct(ClientInterface $client, SerializerInterface $serializer)
    {
        $this->setClient($client);
        $this->setSerializer($serializer);
    }

    /**
     * Sets the HTTP client.
     *
     * @param \Guzzle\Http\ClientInterface $client
     * @return \Cowlby\Rackspace\Cloud\Identity\TokenManager
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Sets the Serializer for entity serialization.
     *
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Cowlby\Rackspace\Cloud\Identity\TokenManager
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        return $this;
    }
}
