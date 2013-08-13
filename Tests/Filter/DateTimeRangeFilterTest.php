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

/**
 * DateTimeRangeFilter tests
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class DateTimeRangeFilterTest extends AbstractDateRangeFilterTest
{
    protected function getFilterClass()
    {
        return '\Sonata\PropelAdminBundle\Filter\DateTimeRangeFilter';
    }

    public function testRenderSettingsHasRightName()
    {
        $settings = $this->filter->getRenderSettings();
        $this->assertEquals('sonata_type_filter_datetime_range', $settings[0]);
    }
}
