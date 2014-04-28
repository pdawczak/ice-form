<?php

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * Returns an array of bundles to registers.
     *
     * @return BundleInterface[] An array of bundle instances.
     *
     * @api
     */
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Ice\FormTestBundle\IceFormTestBundle(),
            new Ice\FormBundle\IceFormBundle(),
            new Misd\GuzzleBundle\MisdGuzzleBundle(),
            new Ice\JanusClientBundle\IceJanusClientBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Ice\MercuryClientBundle\IceMercuryClientBundle(),
            new Ice\MinervaClientBundle\IceMinervaClientBundle(),
            new Ice\VeritasClientBundle\IceVeritasClientBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle()
        ];

        return $bundles;
    }

    /**
     * Loads the container configuration
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     *
     * @api
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }
}
