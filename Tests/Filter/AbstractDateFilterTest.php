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

use Sonata\AdminBundle\Form\Type\Filter\DateType;
use Sonata\PropelAdminBundle\Filter\DateFilter;

use \ModelCriteria;

/**
 * DateFilter base tests
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
abstract class AbstractDateFilterTest extends \PHPUnit_Framework_TestCase
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
    public function testApplyWithInvalidDataDoesNothing($value)
    {
        $query = $this->getQueryMock();

        $this->filter->apply($query, $value);
        $this->assertFalse($this->filter->isActive());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testApplyWithValidData($data, $comparisonType, $normalizedData, $comparisonOperator, $filterOptions)
    {
        $data = array_merge($data, array('type' => $comparisonType));

        $query = $this->getQueryMock();
        $query->expects($this->once())
               ->method('filterBy')
               ->with(
                   $this->equalTo(self::FIELD_NAME),
                   $this->equalTo($normalizedData),
                   $this->equalTo($comparisonOperator)
               );

        foreach ($filterOptions as $name => $value) {
            $this->filter->setOption($name, $value);
        }

        $this->filter->apply($query, $data);
        $this->assertTrue($this->filter->isActive());
    }

    public function invalidDataProvider()
    {
        return array(
            array(null),
            array('string'),
            array(42),
            array(array('foo' => 'dummy value')),
        );
    }

    public function validDataProvider()
    {
        $date = new \DateTime();
        $data = array('value' => $date);

        return array(
            // data, comparisonType, normalizedData, comparisonOperator, filterOptions
            array($data, null,                          $date,                  ModelCriteria::EQUAL,           array()),
            array($data, null,                          $date->getTimestamp(),  ModelCriteria::EQUAL,           array('input_type' => 'timestamp')),
            array($data, DateType::TYPE_EQUAL,          $date,                  ModelCriteria::EQUAL,           array()),
            array($data, DateType::TYPE_GREATER_EQUAL,  $date,                  ModelCriteria::GREATER_EQUAL,   array()),
            array($data, DateType::TYPE_GREATER_THAN,   $date,                  ModelCriteria::GREATER_THAN,    array()),
            array($data, DateType::TYPE_LESS_EQUAL,     $date,                  ModelCriteria::LESS_EQUAL,      array()),
            array($data, DateType::TYPE_LESS_THAN,      $date,                  ModelCriteria::LESS_THAN,       array()),
            array($data, DateType::TYPE_NULL,           $date,                  ModelCriteria::ISNULL,          array()),
            array($data, DateType::TYPE_NOT_NULL,       $date,                  ModelCriteria::ISNOTNULL,       array()),
        );
    }

    protected function getQueryMock()
    {
        $query = $this->getMockBuilder('\Sonata\PropelAdminBundle\Datagrid\ProxyQuery')
            ->disableOriginalConstructor()
            ->setMethods(array('filterBy'))
            ->getMock();

        return $query;
    }

    protected function getFilter($fieldName)
    {
        $filter = $this->getMockBuilder($this->getFilterClass())
            ->setMethods(array('translateFieldName'))
            ->getMock();

        $filter->expects($this->any())
               ->method('translateFieldName')
               ->with($this->equalTo($fieldName))
               ->will($this->returnValue($fieldName));

        $filter->setOption('field_name', $fieldName);

        return $filter;
    }
}
