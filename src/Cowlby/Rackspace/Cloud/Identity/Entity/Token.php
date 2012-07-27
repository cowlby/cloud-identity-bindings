<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class Token extends AbstractEntity
{
    protected $id;

    protected $expires;

    public function __construct()
    {
    }

    public function denormalize(DenormalizerInterface $denormalizer, $data, $format = NULL)
    {
        if (isset($data['expires'])) {
            $data['expires'] = new \DateTime($data['expires']);
        }

        parent::denormalize($denormalizer, $data, $format);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getExpires()
    {
        return $this->expires;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setExpires(\DateTime $expires)
    {
        $this->expires = $expires;
        return $this;
    }

    public function isValid()
    {
        $now = new \DateTime();
        return $now < $this->expires;
    }
}
