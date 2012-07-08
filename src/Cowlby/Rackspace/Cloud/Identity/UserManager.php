<?php

namespace Cowlby\Rackspace\Cloud\Identity;

use Cowlby\Rackspace\Cloud\Common\HydratorInterface;
use Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface;
use Cowlby\Rackspace\Cloud\Identity\Entity;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\BadResponseException;

class UserManager
{
    protected $client;

    protected $hydrator;

    public function __construct(GuzzleClient $client, HydratorInterface $hydrator)
    {
        $this->setClient($client);
        $this->setHydrator($hydrator);
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

    public function getClient()
    {
        return $this->client;
    }

    public function listUsers($name = NULL)
    {
        $request = $this->client->get('users');
        $response = $request->send();

        $json = json_decode($response->getBody(), TRUE);

        if (!isset($json['user'])) {
            throw new \RuntimeException('Method only works for single user.');
        }

        $user = new Entity\User();
        $this->hydrator->hydrateEntity($user, $json['user']);

        return array($user);
    }

    public function getUserById($userId)
    {
        $request = $this->client->get(array('users/{+userId}', array(
            'userId' => $userId
        )));

        $response = $request->send();

        $json = json_decode($response->getBody(), TRUE);

        $user = new Entity\User();
        $this->hydrator->hydrateEntity($user, $json['user']);

        return $user;
    }

    public function listUserGlobalRoles($userId)
    {
        $request = $this->client->get(array('users/{userId}/roles', array(
            'userId' => $userId
        )));

        $response = $request->send();

        $json = json_decode($response->getBody(), TRUE);

        $roles = array();
        foreach ($json['roles'] as $jsonRole) {
            $role = new Entity\Role();
            $this->hydrator->hydrateEntity($role, $jsonRole);
            $roles[] = $role;
        }

        return $roles;
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
        }

        return $this;
    }
}
