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
        if (!$query instanceof ProxyQuery) {
            throw new \RuntimeException('The given query is not supported by this filter.');
        }
    }

    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        return array();
    }
}
