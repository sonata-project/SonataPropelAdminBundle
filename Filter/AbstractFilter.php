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

use Sonata\AdminBundle\Filter\Filter;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

use Sonata\PropelAdminBundle\Datagrid\ProxyQuery;

use ModelCriteria;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
abstract class AbstractFilter extends Filter
{
    /**
     * Apply the filter to the ModelCriteria instance
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
        $map = $this->getCriteriaMap();

        /* @var $query ModelCriteria */
        $query->filterBy($field, sprintf($this->getOption('format', '%s'), $value['value']), $map[$value['type']]);
    }

    public function apply($query, $value)
    {
        if (!$query instanceof ProxyQuery) {
            throw new \RuntimeException('The given query is not supported by this filter.');
        }

        $this->setValue($value);

        /* @var $query ModelCriteria */
        if ($this->isActive()) {
            $column = call_user_func_array(array($query->getModelPeerName(), 'translateFieldName'), array(
                $this->getFieldName(),
                \BasePeer::TYPE_FIELDNAME,
                \BasePeer::TYPE_PHPNAME,
            ));

            $this->filter($query, '', $column, $this->getValue());
        }
    }

    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        return array();
    }

    /**
     * Return the mapping between the selected filter type and the criteria comparison.
     *
     * @return array
     */
    abstract protected function getCriteriaMap();
}
