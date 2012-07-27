<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;

abstract class AbstractEntity implements DenormalizableInterface
{
    public function denormalize(DenormalizerInterface $denormalizer, $data, $format = NULL)
    {
        foreach ($data as $attribute => $value) {
            $setter = 'set' . $attribute;
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }

        return $this;
    }
}
