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
abstract class AbstractDateFilterTest extends AbstractFilterTest
{
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
}
