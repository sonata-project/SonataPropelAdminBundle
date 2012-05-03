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

use Sonata\AdminBundle\Builder\ShowBuilderInterface;

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class ShowBuilder implements ShowBuilderInterface
{
    /**
     * @param array $options
     *
     * @return void
     */
    public function getBaseList(array $options = array())
    {
        // TODO: Implement getBaseList() method.
    }

    /**
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionCollection $list
     * @param null $type
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface $fieldDescription
     * @param \Sonata\AdminBundle\Admin\AdminInterface $admin
     *
     * @return void
     */
    public function addField(FieldDescriptionCollection $list, $type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin)
    {
        // TODO: Implement addField() method.
    }

    /**
     * @param \Sonata\AdminBundle\Admin\AdminInterface $admin
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface $fieldDescription
     *
     * @return void
     */
    public function fixFieldDescription(AdminInterface $admin, FieldDescriptionInterface $fieldDescription)
    {
        // TODO: Implement fixFieldDescription() method.
    }
}
