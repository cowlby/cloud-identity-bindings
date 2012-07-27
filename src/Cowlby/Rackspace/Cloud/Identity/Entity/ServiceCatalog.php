<?php

namespace Cowlby\Rackspace\Cloud\Identity\Entity;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ServiceCatalog extends AbstractEntity
{
    protected $services;

    public function __construct()
    {
        $this->services = array();
    }

    public function denormalize(DenormalizerInterface $denormalizer, $data, $format = NULL)
    {
        foreach ($data as $serviceName => $serviceData) {
            $service = $denormalizer->denormalize($serviceData, __NAMESPACE__ . '\\Service', $format);
            $service->setName($serviceName);
            $this->addService($service);
            unset($data[$serviceName]);
        }

        parent::denormalize($denormalizer, $data, $format);
    }

    public function getServices()
    {
        return $this->services;
    }

    public function getService($name)
    {
        return $this->services[$name];
    }

    public function addService(Service $service)
    {
        $this->services[$service->getName()] = $service;
    }

    public function getCloudDnsService()
    {
        return $this->services['cloudDNS'];
    }
}
