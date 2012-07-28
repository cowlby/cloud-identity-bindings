<?php

namespace Cowlby\Rackspace\Cloud\Identity;

use Pimple;
use Guzzle\Http\Client;
use Cowlby\Rackspace\Common\Cache\NullCacheAdapter;
use Cowlby\Rackspace\Common\Http\GuzzleClientAdapter;
use Cowlby\Rackspace\Cloud\Identity\EntityManager\AuthManager;
use Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface;

class ServiceContainer extends Pimple implements ServiceContainerInterface
{
    const AUTH_ENDPOINT_US = 'https://identity.api.rackspacecloud.com';
    const AUTH_ENDPOINT_UK = 'https://lon.identity.api.rackspacecloud.com';

    /**
     * Constructor.
     *
     * Instantiates and sets up various services to interact with the
     * Cloud Identity API.
     */
    public function __construct(CredentialsInterface $credentials)
    {
        $this['credentials'] = $credentials;

        $this['endpoint'] = self::AUTH_ENDPOINT_US;

        $this['cache'] = $this->share(function($container) {

            return new NullCacheAdapter();
        });

        $this['guzzle'] = $this->share(function($container) {

            $client = new Client($container['endpoint'] . '/v{version}', array(
                'version' => '1.1',
                'curl.CURLOPT_SSL_VERIFYHOST' => FALSE,
                'curl.CURLOPT_SSL_VERIFYPEER' => FALSE
            ));

            $client->setDefaultHeaders(array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ));

            return $client;
        });

        $this['client'] = $this->share(function($container) {
            return new GuzzleClientAdapter($container['guzzle']);
        });

        $this['auth_manager'] = $this->share(function($container) {

            return new AuthManager(
                $container['client'],
                $container['credentials'],
                $container['cache']
            );
        });
    }

    /**
     * {@inheritDoc}
     * @return \Cowlby\Rackspace\Cloud\Identity\EntityManager\AuthManagerInterface
     */
    public function getAuthManager()
    {
        return $this['auth_manager'];
    }
}
