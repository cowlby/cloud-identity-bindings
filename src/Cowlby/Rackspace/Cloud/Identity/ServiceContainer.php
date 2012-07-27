<?php

namespace Cowlby\Rackspace\Cloud\Identity;

use Pimple;
use Cowlby\Rackspace\Cloud\Identity\EntityManager;
use Cowlby\Rackspace\Cloud\Common\Cache\NullCacheAdapter;
use Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface;
use Guzzle\Http\Client;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;

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

        $this['serializer.normalizer'] = $this->share(function($container) {
            return new CustomNormalizer();
        });

        $this['serializer.encoder.json'] = $this->share(function($container) {
            return new JsonEncoder();
        });

        $this['serializer'] = $this->share(function($container) {
            return new Serializer(
                array($container['serializer.normalizer']),
                array('json' => $container['serializer.encoder.json'])
            );
        });

        $this['cache'] = $this->share(function($container) {

            return new NullCacheAdapter();
        });

        $this['client'] = $this->share(function($container) {

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

        $this['auth_manager'] = $this->share(function($container) {

            return new EntityManager\AuthManager(
                $container['client'],
                $container['serializer'],
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
