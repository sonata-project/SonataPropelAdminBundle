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

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class ModelFilter extends AbstractFilter
{
    /**
     * Apply the filter to the ModelCriteria instance
     *
     * @todo Add handling of multiple="true", value becomes a PropelObjectCollection.
     *
     * @param ProxyQueryInterface $query
     * @param string $alias
     * @param string $field
     * @param string $value
     *
     * @return void
     */
    public function filter(ProxyQueryInterface $query, $alias, $field, $value)
    {
        $query->filterBy($field, $value['value']->getId());
    }

    /**
     * Return the mapping between the selected filter type and the criteria comparison.
     *
     * @return array
     */
    protected function getCriteriaMap() { }

    /**
     * Returns the main widget used to render the filter
     *
     * @return array
     */
    public function getRenderSettings()
    {
        return array('sonata_type_filter_default', array(
            'field_type' => 'model',
            'field_options' => $this->getFieldOptions(),
            'label' => $this->getLabel(),
        ));
    }
}
