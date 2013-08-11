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

use Sonata\AdminBundle\Form\Type\Filter\DateType;
use Sonata\AdminBundle\Form\Type\Filter\DateRangeType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

use ModelCriteria;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
abstract class AbstractDateFilter extends AbstractFilter
{
    /**
     * Flag indicating that filter will have range
     * @var boolean
     */
    protected $range = false;

    /**
     * Flag indicating that filter will filter by datetime instead by date
     * @var boolean
     */
    protected $time = false;

    /**
     * {@inheritdoc}
     */
    public function filter(ProxyQueryInterface $query, $alias, $field, $data)
    {
        // check data sanity
        if (!$data || !is_array($data) || !array_key_exists('value', $data) || !$data['value']) {
            return;
        }

        if ($this->range) {
            $this->filterDateRange($query, $field, $data);
        } else {
            $this->filterDate($query, $field, $data);
        }
    }

    /**
     * Filters according to a given date/datetime range.
     *
     * @param ProxyQueryInterface $query
     * @param string              $field
     * @param string              $value
     */
    protected function filterDateRange(ProxyQueryInterface $query, $field, $data)
    {
        // additional data check for ranged items
        if (!array_key_exists('start', $data['value']) || !array_key_exists('end', $data['value'])) {
            return;
        }

        if (!$data['value']['start'] || !$data['value']['end']) {
            return;
        }

        // transform types
        if ($this->getOption('input_type') === 'timestamp') {
            $data['value']['start'] = $data['value']['start'] instanceof \DateTime ? $data['value']['start']->getTimestamp() : 0;
            $data['value']['end'] = $data['value']['end'] instanceof \DateTime ? $data['value']['end']->getTimestamp() : 0;
        }

        // default type for range filter
        $data['type'] = !isset($data['type']) || !is_numeric($data['type']) ?  DateRangeType::TYPE_BETWEEN : $data['type'];

        if ($data['type'] === DateRangeType::TYPE_NOT_BETWEEN) {
            $query
                ->filterBy($field, $data['value']['start'], ModelCriteria::LESS_THAN)
                ->_or()
                ->filterBy($field, $data['value']['end'], ModelCriteria::GREATER_THAN);
        } else {
            $query->filterBy($field, $data['value']['start'], ModelCriteria::GREATER_EQUAL);
            $query->filterBy($field, $data['value']['end'], ModelCriteria::LESS_EQUAL);
        }
    }

    /**
     * Filters according to a given single date/datetime.
     *
     * @param ProxyQueryInterface $query
     * @param string              $field
     * @param string              $value
     */
    protected function filterDate(ProxyQueryInterface $query, $field, $data)
    {
        // default type for simple filter
        $data['type'] = !isset($data['type']) || !is_numeric($data['type']) ? DateType::TYPE_EQUAL : $data['type'];

        // just find an operator and apply query
        $operator = $this->getOperator($data['type']);

        // transform types
        if ($this->getOption('input_type') === 'timestamp') {
            $data['value'] = $data['value'] instanceof \DateTime ? $data['value']->getTimestamp() : 0;
        }

        $query->filterBy($field, $data['value'], $operator);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'input_type' => 'datetime'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getRenderSettings()
    {
        $name = 'sonata_type_filter_date';

        if ($this->time) {
            $name .= 'time';
        }

        if ($this->range) {
            $name .= '_range';
        }

        return array($name, array(
            'field_type'    => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'label'         => $this->getLabel(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function getCriteriaMap()
    {
        return array(
            DateType::TYPE_EQUAL            => ModelCriteria::EQUAL,
            DateType::TYPE_GREATER_EQUAL    => ModelCriteria::GREATER_EQUAL,
            DateType::TYPE_GREATER_THAN     => ModelCriteria::GREATER_THAN,
            DateType::TYPE_LESS_EQUAL       => ModelCriteria::LESS_EQUAL,
            DateType::TYPE_LESS_THAN        => ModelCriteria::LESS_THAN,
            DateType::TYPE_NULL             => ModelCriteria::ISNULL,
            DateType::TYPE_NOT_NULL         => ModelCriteria::ISNOTNULL,
        );
    }

    /**
     * Resolves DataType:: constants to SQL operators
     *
     * @param integer $type
     *
     * @return string
     */
    protected function getOperator($type)
    {
        $type = (int) $type;
        $choices = $this->getCriteriaMap();

        return isset($choices[$type]) ? $choices[$type] : ModelCriteria::EQUAL;
    }

}
