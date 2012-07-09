<?php

namespace Cowlby\Rackspace\Cloud\Identity;

use Pimple;
use Cowlby\Rackspace\Cloud\Common\SetterHydrator;
use Cowlby\Rackspace\Cloud\Common\Cache\NullCacheAdapter;
use Guzzle\Service\Client as GuzzleClient;

class ServiceContainer extends Pimple
{
    const AUTH_ENDPOINT_US = 'https://identity.api.rackspacecloud.com';
    const AUTH_ENDPOINT_UK = 'https://lon.identity.api.rackspacecloud.com';

    public function __construct()
    {
        $this['endpoint'] = self::AUTH_ENDPOINT_US;

        $this['hydrator'] = $this->share(function($container) {

            return new SetterHydrator();
        });

    	$this['cache'] = $this->share(function($container) {

    		return new NullCacheAdapter();
    	});

        $this['client'] = $this->share(function($container) {

            $client = new GuzzleClient($container['endpoint'] . '/v{version}', array(
                'version' => '2.0',
                'curl.CURLOPT_SSL_VERIFYHOST' => FALSE,
                'curl.CURLOPT_SSL_VERIFYPEER' => FALSE
            ));

            $client->setDefaultHeaders(array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ));

            return $client;
        });

        $this['token_manager'] = $this->share(function($container) {

            return new TokenManager(
        	    $container['credentials'],
        		$container['client'],
        		$container['hydrator'],
        		$container['cache']
        	);
        });

        $this['user_manager'] = $this->share(function($container) {

            $container['client']
                ->getDefaultHeaders()
                ->set('X-Auth-Token', $container['token_manager']->getToken()->getId())
            ;

            return new UserManager($container['client'], $container['hydrator']);
        });
    }
}
