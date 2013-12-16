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

use Criteria;
use PropelObjectCollection;

use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class ModelFilter extends AbstractFilter
{
    /**
     * Apply the filter to the ModelCriteria instance
     *
     * @param ProxyQueryInterface $query
     * @param string              $alias
     * @param string              $field
     * @param string              $value
     *
     * @return void
     */
    public function filter(ProxyQueryInterface $query, $alias, $field, $value)
    {
        $map = $this->getCriteriaMap();

        if ($value['value'] instanceof PropelObjectCollection) {
            $comparison = $value['type'] === ChoiceType::TYPE_NOT_CONTAINS ? $map[$value['type']] : Criteria::IN;
            $query->filterBy($field, $value['value'], $comparison);
        } else {
            $comparison = $map[$value['type'] ?: ChoiceType::TYPE_CONTAINS];
            $query->filterBy($field, $value['value'], $comparison);
        }
    }

    /**
     * Return the mapping between the selected filter type and the criteria comparison.
     *
     * @return array
     */
    protected function getCriteriaMap()
    {
        return array(
            ChoiceType::TYPE_CONTAINS       => Criteria::IN,
            ChoiceType::TYPE_NOT_CONTAINS   => Criteria::NOT_IN,
            ChoiceType::TYPE_EQUAL          => Criteria::EQUAL,
        );
    }

    /**
     * Returns the main widget used to render the filter
     *
     * @return array
     */
    public function getRenderSettings()
    {
        return array('sonata_type_filter_default', array(
            'operator_type' => 'sonata_type_equal',
            'field_type'    => 'model',
            'field_options' => $this->getFieldOptions(),
            'label'         => $this->getLabel(),
        ));
    }
}
