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
use Sonata\CoreBundle\Form\Type\BooleanType;

/**
 * BooleanFilter tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class BooleanFilterTest extends AbstractFilterTest
{
    const FIELD_NAME = 'published';

    protected function getFilterClass()
    {
        return '\Sonata\PropelAdminBundle\Filter\BooleanFilter';
    }

    public function validDataProvider()
    {
        $yes = array('value' => BooleanType::TYPE_YES);
        $no = array('value' => BooleanType::TYPE_NO);

        return array(
            // data, comparisonType, normalizedData, comparisonOperator, filterOptions
            array($yes, null,   true, ModelCriteria::EQUAL,     array()),
            array($no,  null,   true, ModelCriteria::NOT_EQUAL, array()),
        );
    }
}
