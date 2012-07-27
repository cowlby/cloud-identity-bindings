<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class Endpoint extends AbstractEntity
{
    protected $region;

    protected $publicURL;

    protected $v1Default;

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getPublicURL()
    {
        return $this->publicURL;
    }

    /**
     * @return bool
     */
    public function getV1Default()
    {
        return $this->v1Default;
    }

    /**
     * @param string $region
     * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Endpoint
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @param string $publicURL
     * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Endpoint
     */
    public function setPublicURL($publicURL)
    {
        $this->publicURL = $publicURL;
        return $this;
    }

    /**
     *
     * @param bool $v1Default
     * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Endpoint
     */
    public function setV1Default($v1Default)
    {
        $this->v1Default = (bool) $v1Default;
        return $this;
    }
}
