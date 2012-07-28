<?php

namespace Cowlby\Rackspace\Cloud\Identity\Http;

interface ClientAdapterInterface
{
    function get($uri, $entityClass = NULL, $body = NULL);

    function post($uri, $entityClass = NULL, $body = NULL);

    function put($uri, $entityClass = NULL, $body = NULL);

    function delete($uri, $entityClass = NULL, $body = NULL);
}
