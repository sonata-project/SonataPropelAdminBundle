<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Tests\Filter;

use ModelCriteria;
use Sonata\AdminBundle\Form\Type\Filter\DateRangeType;

/**
 * DateRangeFilter base tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
abstract class AbstractDateRangeFilterTest extends \PHPUnit_Framework_TestCase
{
    const FIELD_NAME = 'created_at';

    protected $filter;

    abstract protected function getFilterClass();

    public function setUp()
    {
        $this->filter = $this->getFilter(self::FIELD_NAME);
    }

    /**
     * @expectedException           RuntimeException
     * @expectedExceptionMessage    The given query is not supported by this filter.
     */
    public function testApplyWithInvalidQuery()
    {
        $this->filter->apply('not a query', new \DateTime());
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testApplyWithInvalidDataDoesNothing($value, $filterValid)
    {
        $query = $this->getQueryMock();
        $query->expects($this->never())
               ->method('filterBy');

        $this->filter->apply($query, $value);

        if ($filterValid) {
            $this->assertTrue($this->filter->isActive(), 'The filter is active but the query should not be altered');
        } else {
            $this->assertFalse($this->filter->isActive(), 'The filter is not active.');
        }
    }

    /**
     * @dataProvider betweenDataProvider
     */
    public function testApplyBetweenWithValidData($data, $comparisonType, $startNormalizedData, $endNormalizedData, $startComparisonOperator, $endComparisonOperator, $filterOptions)
    {
        $data = array_merge($data, array('type' => $comparisonType));
        $query = $this->getQueryMock();

        $query->expects($this->at(0))
               ->method('getModelName')
               ->will($this->returnValue('not null'));

        $query->expects($this->at(1))
               ->method('filterBy')
               ->with(
                   $this->equalTo(self::FIELD_NAME),
                   $this->equalTo($startNormalizedData),
                   $this->equalTo($startComparisonOperator)
               );

        $query->expects($this->at(2))
               ->method('filterBy')
               ->with(
                   $this->equalTo(self::FIELD_NAME),
                   $this->equalTo($endNormalizedData),
                   $this->equalTo($endComparisonOperator)
               );

        foreach ($filterOptions as $name => $value) {
            $this->filter->setOption($name, $value);
        }

        $this->filter->apply($query, $data);
        $this->assertTrue($this->filter->isActive());
    }

    /**
     * @dataProvider notBetweenDataProvider
     */
    public function testApplyNotBetweenWithValidData($data, $comparisonType, $startNormalizedData, $endNormalizedData, $startComparisonOperator, $endComparisonOperator, $filterOptions)
    {
        $data = array_merge($data, array('type' => $comparisonType));
        $query = $this->getQueryMock();

        $query->expects($this->at(0))
               ->method('getModelName')
               ->will($this->returnValue('not null'));

        $query->expects($this->at(1))
               ->method('filterBy')
               ->with(
                   $this->equalTo(self::FIELD_NAME),
                   $this->equalTo($startNormalizedData),
                   $this->equalTo($startComparisonOperator)
               )
               ->will($this->returnSelf());

        $query->expects($this->at(2))
               ->method('_or')
               ->will($this->returnSelf());

        $query->expects($this->at(3))
               ->method('filterBy')
               ->with(
                   $this->equalTo(self::FIELD_NAME),
                   $this->equalTo($endNormalizedData),
                   $this->equalTo($endComparisonOperator)
               )
               ->will($this->returnSelf());

        foreach ($filterOptions as $name => $value) {
            $this->filter->setOption($name, $value);
        }

        $this->filter->apply($query, $data);
        $this->assertTrue($this->filter->isActive());
    }

    public function invalidDataProvider()
    {
        return array(
            // data, filterValid
            array(null, false),
            array('string', false),
            array(42, false),
            array(array('foo'   => 'dummy value'), false),
            array(array('value' => array('foo'   => 'dummy value')), true),
            array(array('value' => array('start' => 'dummy value')), true),
            array(array('value' => array('end'   => 'dummy value')), true),
            array(array('value' => array('start' => null, 'end' => 'dummy value')), true),
            array(array('value' => array('end'   => null, 'start' => 'dummy value')), true),
        );
    }

    public function betweenDataProvider()
    {
        $start = new \DateTime();
        $end = clone $start;
        $end->modify('+1 week');

        $data = array('value' => array('start' => $start, 'end' => $end));

        return array(
            // data, comparisonType, startNormalizedData, endNormalizedData, startComparisonOperator, endComparisonOperator, filterOptions
            array($data, null,                        $start,                 $end,                 ModelCriteria::GREATER_EQUAL, ModelCriteria::LESS_EQUAL,   array()),
            array($data, null,                        $start->getTimestamp(), $end->getTimestamp(), ModelCriteria::GREATER_EQUAL, ModelCriteria::LESS_EQUAL,   array('input_type' => 'timestamp')),
            array($data, DateRangeType::TYPE_BETWEEN, $start,                 $end,                 ModelCriteria::GREATER_EQUAL, ModelCriteria::LESS_EQUAL,   array()),
            array($data, DateRangeType::TYPE_BETWEEN, $start->getTimestamp(), $end->getTimestamp(), ModelCriteria::GREATER_EQUAL, ModelCriteria::LESS_EQUAL,   array('input_type' => 'timestamp')),
        );
    }

    public function notBetweenDataProvider()
    {
        $start = new \DateTime();
        $end = clone $start;
        $end->modify('+1 week');

        $data = array('value' => array('start' => $start, 'end' => $end));

        return array(
            // data, comparisonType, startNormalizedData, endNormalizedData, startComparisonOperator, endComparisonOperator, filterOptions
            array($data, DateRangeType::TYPE_NOT_BETWEEN, $start,                 $end,                 ModelCriteria::LESS_THAN,     ModelCriteria::GREATER_THAN, array()),
            array($data, DateRangeType::TYPE_NOT_BETWEEN, $start->getTimestamp(), $end->getTimestamp(), ModelCriteria::LESS_THAN,     ModelCriteria::GREATER_THAN, array('input_type' => 'timestamp')),
        );
    }

    protected function getQueryMock()
    {
        $query = $this->getMockBuilder('\Sonata\PropelAdminBundle\Datagrid\ProxyQuery')
            ->disableOriginalConstructor()
            ->setMethods(array('filterBy', '_or', 'getModelName'))
            ->getMock();

        return $query;
    }

    protected function getFilter($fieldName)
    {
        $modelManager = $this->getModelManagerMock();

        $filterClass = $this->getFilterClass();
        $filter = new $filterClass($modelManager);

        $modelManager->expects($this->any())
               ->method('translateFieldName')
               ->with($this->anything(), $this->equalTo($fieldName))
               ->will($this->returnValue($fieldName));

        $filter->initialize('filter', array(
            'field_name' => $fieldName,
        ));

        return $filter;
    }

    protected function getModelManagerMock()
    {
        $manager = $this->getMockBuilder('\Sonata\PropelAdminBundle\Model\ModelManager')
            ->setMethods(array('translateFieldName'))
            ->getMock();

        return $manager;
    }
}
