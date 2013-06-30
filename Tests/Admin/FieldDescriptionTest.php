<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonata\PropelAdminBundle\Tests\Admin;

use Sonata\PropelAdminBundle\Admin\FieldDescription;

/**
 * FieldDescription tests
 */
class FieldDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testAssociationMapping()
    {
        $field = new FieldDescription;
        $field->setAssociationMapping(array(
            'type' => 'integer',
            'fieldName' => 'position'
        ));

        $this->assertEquals('integer', $field->getType());
        $this->assertEquals('integer', $field->getMappingType());

        // cannot overwrite defined definition
        $field->setAssociationMapping(array(
            'type' => 'overwrite?',
            'fieldName' => 'overwritten'
        ));

        $this->assertEquals('integer', $field->getType());
        $this->assertEquals('integer', $field->getMappingType());

        $field->setMappingType('string');
        $this->assertEquals('string', $field->getMappingType());
        $this->assertEquals('integer', $field->getType());
    }

    public function testSetParentAssociationMappings()
    {
        $field = new FieldDescription();
        $field->setParentAssociationMappings(array(array('test')));

        $this->assertEquals(array(array('test')), $field->getParentAssociationMappings());
    }

    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage An association mapping must be an array
     */
    public function testSetParentAssociationMappingsAllowOnlyForArray()
    {
        $field = new FieldDescription();
        $field->setParentAssociationMappings(array('test'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSetAssociationMappingAllowOnlyForArray()
    {
        $field = new FieldDescription();
        $field->setAssociationMapping('test');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSetFieldMappingAllowOnlyForArray()
    {
        $field = new FieldDescription();
        $field->setFieldMapping('test');
    }

    public function testSetFieldMappingSetType()
    {
        $fieldMapping = array(
            'type'         => 'integer',
        );

        $field = new FieldDescription();
        $field->setFieldMapping($fieldMapping);

        $this->assertEquals('integer', $field->getType());
    }

    public function testSetFieldMappingSetMappingType()
    {
        $fieldMapping = array(
            'type'         => 'integer',
        );

        $field = new FieldDescription();
        $field->setFieldMapping($fieldMapping);

        $this->assertEquals('integer', $field->getMappingType());
    }

    public function testGetTargetEntity()
    {
        $assocationMapping = array(
            'type'         => 'integer',
            'targetEntity' => 'someValue'
        );

        $field = new FieldDescription();

        $this->assertNull($field->getTargetEntity());

        $field->setAssociationMapping($assocationMapping);

        $this->assertEquals('someValue', $field->getTargetEntity());
    }

    public function testGetValue()
    {
        $mockedObject = $this->getMock('MockedTestObject', array('myMethod'));
        $mockedObject->expects($this->once())
            ->method('myMethod')
            ->will($this->returnValue('myMethodValue'));

        $field = new FieldDescription();
        $field->setOption('code', 'myMethod');

        $this->assertEquals($field->getValue($mockedObject), 'myMethodValue');
    }

    /**
     * @expectedException Sonata\AdminBundle\Exception\NoValueException
     */
    public function testGetValueWhenCannotRetrieve()
    {
        $mockedObject = $this->getMock('MockedTestObject', array('myMethod'));
        $mockedObject->expects($this->never())
            ->method('myMethod')
            ->will($this->returnValue('myMethodValue'));

        $field = new FieldDescription();

        $this->assertEquals($field->getValue($mockedObject), 'myMethodValue');
    }

    public function testIsIdentifierFromFieldMapping()
    {
        $fieldMapping = array(
            'type'      => 'integer',
            'fieldName' => 'position',
            'id'        => 'someId'
        );

        $field = new FieldDescription();
        $field->setFieldMapping($fieldMapping);

        $this->assertEquals('someId', $field->isIdentifier());
    }
}
