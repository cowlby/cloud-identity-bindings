<?php

namespace Cowlby\Rackspace\Cloud\Identity\Http;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GuzzleClientAdapter implements ClientAdapterInterface
{
    protected $client;

    protected $serializer;

    public function __construct(ClientInterface $client, SerializerInterface $serializer)
    {
        $this->setClient($client);
        $this->setSerializer($serializer);
    }

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        return $this;
    }

    public function get($uri, $entityClass = NULL, $body = NULL)
    {
        $request = $this->client->get($uri, NULL, $body);
        return $this->send($request, $entityClass);
    }

    public function post($uri, $entityClass = NULL, $body = NULL)
    {
        $request = $this->client->post($uri, NULL, $body);
        return $this->send($request, $entityClass);
    }

    public function put($uri, $entityClass = NULL, $body = NULL)
    {
        $request = $this->client->put($uri, NULL, $body);
        return $this->send($request, $entityClass);
    }

    public function delete($uri, $entityClass = NULL, $body = NULL)
    {
        $request = $this->client->delete($uri, NULL, $body);
        return $this->send($request, $entityClass);
    }

    protected function send(RequestInterface $request, $entityClass = NULL)
    {
        $response = $this->client->send($request);

        $retVal = $response->getBody();

        if ($entityClass !== NULL) {
            $retVal = $this->serializer->deserialize($retVal, $entityClass, 'json');
        }

        return $retVal;
    }
}
