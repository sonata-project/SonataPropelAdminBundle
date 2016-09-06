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
     * @return \PropelObjectCollection
     */
    public function getResults()
    {
        return $this->getQuery()->execute();
    }

    /**
     * Initialize the Pager.
     *
     * @todo Add handling of parameters, if given
     */
    public function init()
    {
        $this->resetIterator();

        $this->setNbResults($this->computeNbResults());

        $this->getQuery()->setFirstResult(null);
        $this->getQuery()->setMaxResults(null);

        if (0 == $this->getPage() || 0 == $this->getMaxPerPage() || 0 == $this->getNbResults()) {
            $this->setLastPage(0);
        } else {
            $offset = ($this->getPage() - 1) * $this->getMaxPerPage();

            $this->setLastPage((int) ceil($this->getNbResults() / $this->getMaxPerPage()));

            $this->getQuery()->setFirstResult((int) $offset);
            $this->getQuery()->setMaxResults((int) $this->getMaxPerPage());
        }
    }

    protected function computeNbResults()
    {
        $nbResultsQuery = clone $this->getQuery();
        $nbResultsQuery->limit(null)->offset(null);

        return $nbResultsQuery->count();
    }
}
