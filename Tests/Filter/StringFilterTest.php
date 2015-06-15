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
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\PropelAdminBundle\Filter\StringFilter;

/**
 * StringFilter tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class StringFilterTest extends AbstractFilterTest
{
    const FIELD_NAME = 'title';

    protected function getFilterClass()
    {
        return '\Sonata\PropelAdminBundle\Filter\StringFilter';
    }

    public function validDataProvider()
    {
        $search = 'foobar';
        $normalizedSearch = '%foobar%';
        $data = array('value' => $search);

        return array(
            // data, comparisonType, normalizedData, comparisonOperator, filterOptions
            array($data, '',                            $normalizedSearch, ModelCriteria::LIKE,     array()),
            array($data, null,                          $normalizedSearch, ModelCriteria::LIKE,     array()),
            array($data, ChoiceType::TYPE_CONTAINS,     $normalizedSearch, ModelCriteria::LIKE,     array()),
            array($data, ChoiceType::TYPE_EQUAL,        $search,           ModelCriteria::EQUAL,    array()),
            array($data, ChoiceType::TYPE_NOT_CONTAINS, $normalizedSearch, ModelCriteria::NOT_LIKE, array()),
        );
    }
}
