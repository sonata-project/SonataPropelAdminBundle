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

use ModelCriteria;
use PropelObjectCollection;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 *
 * @todo Add handling of sort, limit and offset properties
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
     * @param array $params
     * @param string|null $hydrationMode
     *
     * @return PropelObjectCollection
     */
    public function execute(array $params = array(), $hydrationMode = null)
    {
        $this->result = $this->query->find();

        return $this->result;
    }

    /**
     * Forward calls to the ModelCriteria.
     *
     * @param string $name
     * @param array $args
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
        $this->sortBy = array($parentAssociationMappings, $fieldMapping);

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

        return $this;
    }

    public function getMaxResults()
    {
        return $this->maxResults;
    }

    public function setFirstResult($firstResult)
    {
        $this->firstResult = $firstResult;

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
        // TODO: Implement getUniqueParameterId() method.
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
}
