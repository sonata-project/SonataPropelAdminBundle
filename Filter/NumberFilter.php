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

use Sonata\AdminBundle\Form\Type\Filter\NumberType;

use ModelCriteria;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class NumberFilter extends AbstractFilter
{
    /**
     * @return array
     */
    public function getRenderSettings()
    {
        return array('sonata_type_filter_number', array(
            'field_type'    => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'label'         => $this->getLabel(),
        ));
    }

    protected function getCriteriaMap()
    {
        return array(
            NumberType::TYPE_GREATER_EQUAL  => ModelCriteria::GREATER_EQUAL,
            NumberType::TYPE_GREATER_THAN   => ModelCriteria::GREATER_THAN,
            NumberType::TYPE_EQUAL          => ModelCriteria::EQUAL,
            NumberType::TYPE_LESS_EQUAL     => ModelCriteria::LESS_EQUAL,
            NumberType::TYPE_LESS_THAN      => ModelCriteria::LESS_THAN,
        );
    }
}
