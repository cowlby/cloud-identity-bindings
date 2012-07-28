<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class Auth extends AbstractEntity
{
    /**
     * @var Token
     */
    protected $token;

    /**
     * @var ServiceCatalog
     */
    protected $serviceCatalog;

    /**
     * {@inheritDoc}
     */
    public function denormalize(DenormalizerInterface $denormalizer, $data, $format = NULL)
    {
        $tokenClass = __NAMESPACE__ . '\\Token';
        $this->setToken($denormalizer->denormalize($data['auth']['token'], $tokenClass, $format));

        $catalogClass = __NAMESPACE__ . '\\ServiceCatalog';
        $this->setServiceCatalog($denormalizer->denormalize($data['auth']['serviceCatalog'], $catalogClass, $format));

        unset($data['auth']);

        parent::denormalize($denormalizer, $data, $format);
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        return $this->token->isValid();
    }

    /**
     * @return Token
     */
    public function getToken ()
    {
        return $this->token;
    }

    /**
     * @return ServiceCatalog
     */
    public function getServiceCatalog ()
    {
        return $this->serviceCatalog;
    }

    /**
     * @param Token $token
     * @return Auth
     */
    public function setToken (Token $token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param ServiceCatalog $serviceCatalog
     * @return Auth
     */
    public function setServiceCatalog (ServiceCatalog $serviceCatalog)
    {
        $this->serviceCatalog = $serviceCatalog;
        return $this;
    }
}
