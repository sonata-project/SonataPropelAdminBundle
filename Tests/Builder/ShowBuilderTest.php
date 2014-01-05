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

use Symfony\Component\Form\Guess\TypeGuess;

use Sonata\PropelAdminBundle\Builder\ShowBuilder;
use Sonata\PropelAdminBundle\Admin\FieldDescription;

/**
 * ShowBuilder tests
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ShowBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $admin;
    protected $typeGuesser;
    protected $list;

    public function setUp()
    {
        // configure the admin
        $this->admin = $this->getMock('Sonata\AdminBundle\Admin\AdminInterface');

        // configure the typeGuesser
        $this->typeGuesser = $this->getMock('Sonata\AdminBundle\Guesser\TypeGuesserInterface');

        // configure the fields list
        $this->list = $this->getMock('Sonata\AdminBundle\Admin\FieldDescriptionCollection');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testCantAddFieldWithoutType()
    {
        $modelManager = $this->getMock('Sonata\AdminBundle\Model\ModelManagerInterface');
        $this->admin
            ->expects($this->once())
            ->method('getModelManager')
            ->will($this->returnValue($modelManager));

        $this->typeGuesser
            ->expects($this->once())
            ->method('guessType')
            ->will($this->returnValue(new TypeGuess(null, array(), TypeGuess::HIGH_CONFIDENCE)));

        $builder = new ShowBuilder($this->typeGuesser);
        $field = new FieldDescription();

        $builder->addField($this->list, null, $field, $this->admin);
    }

    /**
     * @group           templates
     * @dataProvider    addFieldFixesTemplateProvider
     */
    public function testAddFieldFixesTemplate($templatesMap, $field, $type, $expectedTemplate)
    {
        $builder = new ShowBuilder($this->typeGuesser, $templatesMap);
        $builder->addField($this->list, $type, $field, $this->admin);

        $this->assertSame($expectedTemplate, $field->getTemplate());
    }

    public function addFieldFixesTemplateProvider()
    {
        $templatesMap = array(
            'text'      => 'textTemplate.html.twig',
            'integer'   => 'integerTemplate.html.twig',
        );

        // configure the fields descriptions
        $field = new FieldDescription();
        $field->setTemplate('customTextTemplate.html.twig');

        return array(
            array($templatesMap, new FieldDescription(), 'text',    'textTemplate.html.twig'),
            array($templatesMap, new FieldDescription(), 'integer', 'integerTemplate.html.twig'),
            array($templatesMap, $field,                 'text',    'customTextTemplate.html.twig'),
            array($templatesMap, new FieldDescription(), 'boolean',  null),
        );
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testAddFieldFixesFieldDescription($field, $givenOptions, $expectedOptions)
    {
        $field->setOptions($givenOptions);

        $builder = new ShowBuilder($this->typeGuesser);
        $builder->addField($this->list, 'text', $field, $this->admin);

        foreach ($expectedOptions as $option => $value) {
            $this->assertSame($value, $field->getOption($option), 'Testing option ' . $option);
        }
    }

    public function optionsProvider()
    {
        $field = new FieldDescription();
        $field->setName('my_field');

        return array(
            /****************************
             * Code and label related options
             ***************************/
            // the default code is the field's name
            array(
                $field,
                array(),
                array('code' => 'my_field')
            ),
            // the default label is the field's name
            array(
                $field,
                array(),
                array('label' => 'my_field')
            ),
            // code and label are updated if given
            array(
                $field,
                array('code' => 'super code', 'label' => 'super label'),
                array('code' => 'super code', 'label' => 'super label')
            ),
        );
    }
}
