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
     * @param string              $alias
     * @param string              $field
     * @param string              $value
     *
     * @return void
     */
    public function filter(ProxyQueryInterface $query, $alias, $field, $value)
    {
        $map = $this->getCriteriaMap();

        /* @var $query ModelCriteria */
        $query->filterBy($field, sprintf($this->getOption('format', '%s'), $value['value']), $map[$value['type']]);
    }

    /**
     * {@inheritdoc}
     */
    public function apply($query, $value)
    {
        if (!$query instanceof ProxyQuery) {
            throw new \RuntimeException('The given query is not supported by this filter.');
        }

        $this->setValue($value);

        /* @var $query ModelCriteria */
        if (!$this->isActive()) {
            return;
        }

        $column = $this->getFieldName();
        if (!$query->hasMethod('filterBy'.ucfirst($this->getFieldName()))) {
            $column = $this->translateFieldName($query, $this->getFieldName());
        }

        $this->filter($query, '', $column, $this->getValue());
    }

    /**
     * @return string
     */
    public function isActive()
    {
        $values = $this->getValue();

        return is_array($values) && !empty($values['value']);
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

    /**
     * Translates the field name to its phpName equivalent.
     *
     * @param ProxyQueryInterface $query
     * @param string              $fieldName The field name to translate.
     *
     * @return string
     */
    protected function translateFieldName($query, $fieldName)
    {
        $tableMap = call_user_func(array($query->getModelPeerName(), 'getTableMap'));

        if ($tableMap->hasColumn($fieldName)) {
            return $tableMap->getColumn($fieldName)->getPhpName();
        }

        if ($tableMap->hasColumnByInsensitiveCase($fieldName)) {
            return $tableMap->getColumnByInsensitiveCase($fieldName)->getPhpName();
        }

        throw new \RuntimeException(sprintf('Unknown field "%s"', $fieldName));
    }
}
