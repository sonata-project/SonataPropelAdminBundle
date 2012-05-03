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

use Sonata\AdminBundle\Datagrid\Pager as BasePager;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class Pager extends BasePager
{
    /**
     * @var ProxyQuery
     */
    protected $query = null;

    /**
     * Returns an array of results on the given page.
     *
     * @return array
     */
    public function getResults()
    {
        $this->results = $this->getQuery()->execute();
        $this->setNbResults(count($this->results));

        return $this->results;
    }

    /**
     * Initialize the Pager.
     *
     * @todo Add handling of parameters, if given.
     *
     * @return void
     */
    public function init()
    {
        $this->resetIterator();

        $this->getQuery()->setFirstResult(null);
        $this->getQuery()->setMaxResults(null);
    }

    /**
     * Return the query for this pager.
     *
     * @return ProxyQuery
     */
    public function getQuery()
    {
        return $this->query;
    }
}
