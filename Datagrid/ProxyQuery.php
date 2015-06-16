<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Datagrid;

use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

//use ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use PropelObjectCollection;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 *
 * @todo Add handling of sort
 */
class ProxyQuery implements ProxyQueryInterface
{
    protected $sortBy;
    protected $sortOrder;
    protected $maxResults;
    protected $firstResult;

    /**
     * @var ModelCriteria
     */
    protected $query;

    /**
     * @var PropelObjectCollection
     */
    protected $result;

    protected $uniqueParameterId = 0;

    /**
     * Constructor.
     *
     * @param \ModelCriteria $query
     */
    public function __construct(ModelCriteria $query)
    {
        $this->query = $query;
    }

    /**
     * Execute the configured query.
     *
     * @param array       $params
     * @param string|null $hydrationMode
     *
     * @return PropelObjectCollection
     */
    public function execute(array $params = array(), $hydrationMode = null)
    {
        if ($sortBy = $this->getSortBy()) {
            $this->query->orderBy($sortBy, $this->getSortOrder());
        }

        $this->result = $this->query->find();

        return $this->result;
    }

    /**
     * Apply a filter to a given column.
     *
     * Calls the filterBy{Column}() method if it exists or fallbacks to
     * ModelCriteria's filterBy() otherwise.
     *
     * @param string $column     The column on which we apply a filter (must be its phpName).
     * @param mixed  $value      The value to filter by.
     * @param string $comparison The comparison operator.
     *
     * @return ProxyQuery
     */
    public function filterBy($column, $value, $comparison = \Criteria::EQUAL)
    {
        $method = 'filterBy';
        $args = array($column, $value, $comparison);

        if (method_exists($this->query, 'filterBy' . ucfirst($column))) {
            $method = 'filterBy' . ucfirst($column);
            $args = array($value, $comparison);
        }

        call_user_func_array(array($this->query, $method), $args);

        return $this;
    }

    /**
     * Forward calls to the ModelCriteria.
     *
     * @param string $name
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array(array($this->query, $name), $args);
    }

    /**
     * Return the count of the current query.
     *
     * @return int
     */
    public function getSingleScalarResult()
    {
        return $this->query->count();
    }

    public function setSortBy($parentAssociationMappings, $fieldMapping)
    {
        // @todo: handle $parentAssociationMappings
        $this->sortBy = $fieldMapping['fieldName'];

        return $this;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
        $this->query->limit((int) $maxResults);

        return $this;
    }

    public function getMaxResults()
    {
        return $this->maxResults;
    }

    public function setFirstResult($firstResult)
    {
        $this->firstResult = $firstResult;
        $this->query->offset((int) $firstResult);

        return $this;
    }

    public function getFirstResult()
    {
        return $this->firstResult;
    }

    public function __clone()
    {
        $this->query = clone $this->query;
    }

    /**
     * @return mixed
     */
    public function getUniqueParameterId()
    {
        return $this->uniqueParameterId++;
    }

    /**
     * @param array $associationMappings
     *
     * @return mixed
     */
    public function entityJoin(array $associationMappings)
    {
        // TODO: Implement entityJoin() method.
    }

    /**
     * @return \ModelCriteria
     */
    public function getQuery()
    {
        return $this->query;
    }

    public function hasMethod($method)
    {
        return method_exists($this, $method) || method_exists($this->query, $method);
    }
}
