<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sonata\AdminBundle\DependencyInjection\AbstractSonataAdminExtension;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class SonataPropelAdminExtension extends AbstractSonataAdminExtension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->fixTemplatesConfiguration($config, $container);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('propel.xml');
        $loader->load('filter_types.xml');

        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, $config);

        // save the form themes
        $container->setParameter('sonata_propel_admin.templates', $config['templates']);

        // define the templates
        $container->getDefinition('sonata.admin.builder.propel_list')
            ->replaceArgument(1, $config['templates']['types']['list']);

        $container->getDefinition('sonata.admin.builder.propel_show')
            ->replaceArgument(1, $config['templates']['types']['show']);
    }
}
