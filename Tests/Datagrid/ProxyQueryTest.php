<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonata\PropelAdminBundle\Tests\Datagrid;

use Sonata\PropelAdminBundle\Datagrid\ProxyQuery;
use Sonata\PropelAdminBundle\Tests\Functionnal\WebTestCase;

/**
 * ProxyQuery tests
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ProxyQueryTest extends WebTestCase
{
    public function testFilterByCallsQueryClassesIfMethodExists()
    {
        $query = $this->getMockBuilder('\Sonata\TestBundle\Model\BlogPostQuery', array('filterByTitle'))
            ->disableOriginalConstructor()
            ->getMock();
        $query->expects($this->once())
            ->method('filterByTitle')
            ->with(
                $this->equalTo('dummy title'),
                $this->equalTo(\Criteria::EQUAL)
            );

        $proxy = new ProxyQuery($query);
        $proxy->filterBy('Title', 'dummy title');
    }

    public function testFilterByCallsModelCriteriaIfMethodDoesntExist()
    {
        $query = $this->getMockBuilder('\Sonata\TestBundle\Model\BlogPostQuery', array('filterBy'))
            ->disableOriginalConstructor()
            ->getMock();
        $query->expects($this->once())
            ->method('filterBy')
            ->with(
                $this->equalTo('Slug'),
                $this->equalTo('slug'),
                $this->equalTo(\Criteria::EQUAL)
            );

        $proxy = new ProxyQuery($query);
        $proxy->filterBy('Slug', 'slug');
    }
}
