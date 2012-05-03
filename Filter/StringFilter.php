<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Filter;

use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

use ModelCriteria;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class StringFilter extends AbstractFilter
{
    public function filter(ProxyQueryInterface $query, $alias, $field, $value)
    {
        parent::filter($query, $alias, $field, $value);
    }

    public function apply($query, $value)
    {
        // TODO: Implement apply() method.
    }

    /**
     * @return array
     */
    public function getRenderSettings()
    {
        return array('sonata_type_filter_choice', array(
            'field_type' => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'label' => $this->getLabel(),
        ));
    }
}
