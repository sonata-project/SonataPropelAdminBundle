<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @codeCoverageIgnore
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),

            new Propel\PropelBundle\PropelBundle(),

            new Sonata\CoreBundle\SonataCoreBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\PropelAdminBundle\SonataPropelAdminBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/SonataPropelAdminBundle/cache';
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return sys_get_temp_dir().'/SonataPropelAdminBundle/logs';
    }
}
