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

use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\PropelAdminBundle\Filter\ModelFilter;

use \Criteria;
use \PropelObjectCollection;

/**
 * ModelFilter tests
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ModelFilterTest extends AbstractFilterTest
{
    protected function getFilterClass()
    {
        return '\Sonata\PropelAdminBundle\Filter\ModelFilter';
    }

    public function validDataProvider()
    {
        $user = new User();
        $user->id = 42;

        $collection = new PropelObjectCollection();
        $collection->append($user);

        $data = array('value' => $user);
        $dataCollection = array('value' => $collection);

        return array(
            //    data,             comparisonType, normalizedData, comparisonOperator, filterOptions
            array($data,            null,           42,             Criteria::EQUAL,    array()),
            array($dataCollection,  null,           $collection,    Criteria::IN,       array()),
        );
    }
}

class User
{
    public $id;

    public function getId()
    {
        return $this->id;
    }
}
