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
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;

use ModelCriteria;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class StringFilter extends AbstractFilter
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
        if (empty($value['type'])) {
            $value['type'] = ChoiceType::TYPE_CONTAINS;
        }

        if (ChoiceType::TYPE_EQUAL === $value['type']) {
            $this->setOption('format', '%s');
        }

        parent::filter($query, $alias, $field, $value);
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

    public function getDefaultOptions()
    {
        return array(
            'format' => '%%%s%%',
        );
    }

    protected function getCriteriaMap()
    {
        return array(
            ChoiceType::TYPE_CONTAINS => ModelCriteria::LIKE,
            ChoiceType::TYPE_NOT_CONTAINS => ModelCriteria::NOT_LIKE,
            ChoiceType::TYPE_EQUAL => ModelCriteria::EQUAL,
        );
    }
}
