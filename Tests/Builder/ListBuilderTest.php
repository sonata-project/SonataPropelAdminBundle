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

use Sonata\PropelAdminBundle\Builder\ListBuilder;
use Sonata\PropelAdminBundle\Admin\FieldDescription;

/**
 * ListBuilder tests
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ListBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider addFieldFixesTemplateProvider
     */
    public function testAddFieldFixesTemplate($templatesMap, $field, $type, $expectedTemplate)
    {
        // configure the admin
        $admin = $this->getMock('Sonata\AdminBundle\Admin\AdminInterface');

        // configure the typeGuesser
        $typeGuesser = $this->getMock('Sonata\AdminBundle\Guesser\TypeGuesserInterface');

        // configure the fields list
        $list = $this->getMock('Sonata\AdminBundle\Admin\FieldDescriptionCollection');

        // and test!
        $builder = new ListBuilder($typeGuesser, $templatesMap);
        $builder->addField($list, $type, $field, $admin);

        $this->assertSame($expectedTemplate, $field->getTemplate());
    }

    public function addFieldFixesTemplateProvider()
    {
        $templatesMap = array(
            'text' => 'textTemplate.html.twig',
            'integer' => 'integerTemplate.html.twig',
        );

        // configure the fields descriptions
        $field = new FieldDescription();
        $field->setTemplate('customTextTemplate.html.twig');

        return array(
            array($templatesMap, new FieldDescription(), 'text', 'textTemplate.html.twig'),
            array($templatesMap, new FieldDescription(), 'integer', 'integerTemplate.html.twig'),
            array($templatesMap, $field, 'text', 'customTextTemplate.html.twig'),
            array($templatesMap, new FieldDescription(), 'boolean', null),
        );
    }
}
