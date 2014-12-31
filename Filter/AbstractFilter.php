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
use Sonata\AdminBundle\Filter\Filter;
use Sonata\PropelAdminBundle\Model\ModelManager;

use Sonata\PropelAdminBundle\Datagrid\ProxyQuery;

use ModelCriteria;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
abstract class AbstractFilter extends Filter
{
    protected $modelManager;

    public function __construct(ModelManager $modelManager)
    {
        $this->modelManager = $modelManager;
    }

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

        $comparison = (!empty($map[$value['type']])) ? $map[$value['type']] : null;

        /* @var $query ModelCriteria */
        if (empty($comparison)) {
            $query->filterBy($field, sprintf($this->getOption('format', '%s'), $value['value']));
        } else {
            $query->filterBy($field, sprintf($this->getOption('format', '%s'), $value['value']), $comparison);
        }
        $query->_or();
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
        return $this->modelManager->translateFieldName($query->getModelName(), $fieldName);
    }
}
