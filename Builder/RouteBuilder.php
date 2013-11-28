<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Builder;

use Sonata\AdminBundle\Builder\RouteBuilderInterface;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class RouteBuilder implements RouteBuilderInterface
{
    /**
     * @param \Sonata\AdminBundle\Admin\AdminInterface  $admin
     * @param \Sonata\AdminBundle\Route\RouteCollection $collection
     */
    public function build(AdminInterface $admin, RouteCollection $collection)
    {
        // TODO: Implement build() method.
    }
}
