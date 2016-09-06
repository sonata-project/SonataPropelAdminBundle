<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;

abstract class Admin extends AbstractAdmin
{
    /**
     * Returns the baseRoutePattern used to generate the routing information.
     *
     * @throws \RuntimeException
     *
     * @return string the baseRoutePattern used to generate the routing information
     */
    public function getBaseRoutePattern()
    {
        try {
            return parent::getBaseRoutePattern();
        } catch (\RuntimeException $e) {
            if (!$this->baseRoutePattern) {
                preg_match('@([A-Za-z0-9]*)\\\([A-Za-z0-9]*)Bundle\\\(Propel)\\\(.*)@', $this->getClass(), $matches);

                if (!$matches) {
                    throw new \RuntimeException(sprintf('Please define a default `baseRoutePattern` value for the admin class `%s`', get_class($this)));
                }

                if ($this->isChild()) { // the admin class is a child, prefix it with the parent route name
                    $this->baseRoutePattern = sprintf('%s/{id}/%s',
                        $this->getParent()->getBaseRoutePattern(),
                        $this->urlize($matches[4], '-')
                    );
                } else {
                    $this->baseRoutePattern = sprintf('/%s/%s/%s',
                        $this->urlize($matches[1], '-'),
                        $this->urlize($matches[2], '-'),
                        $this->urlize($matches[4], '-')
                    );
                }
            }

            return $this->baseRoutePattern;
        }
    }

    /**
     * Returns the baseRouteName used to generate the routing information.
     *
     * @throws \RuntimeException
     *
     * @return string the baseRouteName used to generate the routing information
     */
    public function getBaseRouteName()
    {
        try {
            return parent::getBaseRouteName();
        } catch (\RuntimeException $e) {
            if (!$this->baseRouteName) {
                preg_match('@([A-Za-z0-9]*)\\\([A-Za-z0-9]*)Bundle\\\(Propel)\\\(.*)@', $this->getClass(), $matches);

                if (!$matches) {
                    throw new \RuntimeException(sprintf('Please define a default `baseRouteName` value for the admin class `%s`', get_class($this)));
                }

                if ($this->isChild()) { // the admin class is a child, prefix it with the parent route name
                    $this->baseRouteName = sprintf('%s_%s',
                        $this->getParent()->getBaseRouteName(),
                        $this->urlize($matches[4])
                    );
                } else {
                    $this->baseRouteName = sprintf('admin_%s_%s_%s',
                        $this->urlize($matches[1]),
                        $this->urlize($matches[2]),
                        $this->urlize($matches[4])
                    );
                }
            }

            return $this->baseRouteName;
        }
    }
}
