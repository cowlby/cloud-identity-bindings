<?php

namespace Cowlby\Rackspace\Cloud\Identity;

use Pimple;
use Cowlby\Rackspace\Cloud\Common\SetterHydrator;
use Guzzle\Service\Client as GuzzleClient;

class ServiceContainer extends Pimple
{
    const AUTH_ENDPOINT_US = 'https://identity.api.rackspacecloud.com';
    const AUTH_ENDPOINT_UK = 'https://lon.identity.api.rackspacecloud.com';

    public function __construct()
    {
        $this['api.version'] = '2.0';

        $this['api.content_type'] = 'application/json';

        $this['api.accept'] = 'application/json';

        $this['hydrator'] = $this->share(function($container) {

            return new SetterHydrator();
        });

        $this['client'] = $this->share(function($container) {

            $client = new GuzzleClient($container['auth_endpoint'] . '/v{version}', array(
                'version' => $container['api.version'],
                'curl.CURLOPT_SSL_VERIFYHOST' => FALSE,
                'curl.CURLOPT_SSL_VERIFYPEER' => FALSE
            ));

            $client->setDefaultHeaders(array(
                'Content-Type' => $container['api.content_type'],
                'Accept' => $container['api.accept']
            ));

            return $client;
        });

        $this['token_manager'] = $this->share(function($container) {

            return new TokenManager(
                $container['credentials'],
                $container['client'],
                $container['hydrator']
            );
        });

        $this['user_manager'] = $this->share(function($container) {

            $xAuthToken = $container['token_manager']->getToken()->getId();
            $container['client']->getDefaultHeaders()->set('X-Auth-Token', $xAuthToken);

            return new UserManager(
                $container['client'],
                $container['hydrator']
            );
        });
    }
}
