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
}
