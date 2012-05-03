<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Admin;

use Sonata\AdminBundle\Admin\BaseFieldDescription;
use Sonata\AdminBundle\Admin\AdminInterface;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class FieldDescription extends BaseFieldDescription
{
    /**
     * Define the association mapping definition
     *
     * @param array $associationMapping
     *
     * @return void
     */
    public function setAssociationMapping($associationMapping)
    {
        // TODO: Implement setAssociationMapping() method.
    }

    /**
     * return the related Target Entity
     *
     * @return string|null
     */
    public function getTargetEntity()
    {
        // TODO: Implement getTargetEntity() method.

        return null;
    }

    /**
     * set the field mapping information
     *
     * @param array $fieldMapping
     *
     * @return void
     */
    public function setFieldMapping($fieldMapping)
    {
        // TODO: Implement setFieldMapping() method.

        $this->fieldMapping = $fieldMapping;

        $this->type = $fieldMapping['type'];
        $this->mappingType = $fieldMapping['type'];
        $this->fieldName = $fieldMapping['fieldName'];
    }

    /**
     * return true if the FieldDescription is linked to an identifier field
     *
     * @return bool
     */
    public function isIdentifier()
    {
        // TODO: Implement isIdentifier() method.

        return isset($this->fieldMapping['id']) ? $this->fieldMapping['id'] : false;
    }
}
