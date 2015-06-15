<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Tests\Builder;

use Sonata\PropelAdminBundle\Builder\DatagridBuilder;

/**
 * DatagridBuilder tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class DatagridBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testTextFieldsAreMadeSearchable()
    {
        $formFactory = $this->getMockBuilder('\Symfony\Component\Form\FormFactory')->disableOriginalConstructor()->getMock();
        $filterFactory = $this->getMock('\Sonata\AdminBundle\Filter\FilterFactoryInterface');
        $typeGuesser = $this->getMock('Sonata\AdminBundle\Guesser\TypeGuesserInterface');
        $admin = $this->getMock('Sonata\AdminBundle\Admin\AdminInterface');
        $fieldDescription = $this->getMock('Sonata\AdminBundle\Admin\FieldDescriptionInterface');

        $fieldDescription
            ->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('text'));

        $fieldDescription
            ->expects($this->once())
            ->method('getOption')
            ->with('global_search', true) // we still look the given options
            ->will($this->returnValue(true));

        $fieldDescription
            ->expects($this->once())
            ->method('setOption')
            ->with('global_search', true);

        // and test!
        $builder = new DatagridBuilder($formFactory, $filterFactory, $typeGuesser);
        $builder->fixFieldDescription($admin, $fieldDescription);
    }

    public function testNonTextFieldsAreNotMadeSearchable()
    {
        $formFactory = $this->getMockBuilder('\Symfony\Component\Form\FormFactory')->disableOriginalConstructor()->getMock();
        $filterFactory = $this->getMock('\Sonata\AdminBundle\Filter\FilterFactoryInterface');
        $typeGuesser = $this->getMock('Sonata\AdminBundle\Guesser\TypeGuesserInterface');
        $admin = $this->getMock('Sonata\AdminBundle\Admin\AdminInterface');
        $fieldDescription = $this->getMock('Sonata\AdminBundle\Admin\FieldDescriptionInterface');

        $fieldDescription
            ->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('integer'));

        $fieldDescription
            ->expects($this->never())
            ->method('getOption');

        $fieldDescription
            ->expects($this->never())
            ->method('setOption');

        // and test!
        $builder = new DatagridBuilder($formFactory, $filterFactory, $typeGuesser);
        $builder->fixFieldDescription($admin, $fieldDescription);
    }
}
