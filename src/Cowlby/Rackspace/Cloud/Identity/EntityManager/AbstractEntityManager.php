<?php

namespace Cowlby\Rackspace\Cloud\Identity\EntityManager;

use Cowlby\Rackspace\Cloud\Identity\Http\ClientAdapterInterface;

abstract class AbstractEntityManager
{
    /**
     * @var ClientAdapterInterface
     */
    protected $client;

    /**
     * Constructor.
     *
     * @param ClientAdapterInterface $client
     */
    public function __construct(ClientAdapterInterface $client)
    {
        $this->setClient($client);
    }

    /**
     * Sets the HTTP client.
     *
     * @param ClientAdapterInterface $client
     * @return \Cowlby\Rackspace\Cloud\Identity\AbstractEntityManager
     */
    public function setClient(ClientAdapterInterface $client)
    {
        $this->client = $client;
        return $this;
    }
}
