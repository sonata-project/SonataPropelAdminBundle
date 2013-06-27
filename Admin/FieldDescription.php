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

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class FieldDescription extends BaseFieldDescription
{
    /**
     * Define the association mapping definition
     *
     * @param array $associationMapping
     */
    public function setAssociationMapping($associationMapping)
    {
        if (!is_array($associationMapping)) {
            throw new \RuntimeException('The association mapping must be an array');
        }

        $this->associationMapping = $associationMapping;

        $this->type        = $this->type ? : $associationMapping['type'];
        $this->mappingType = $this->mappingType ? : $associationMapping['type'];
    }

    /**
     * return the related Target Entity
     *
     * @return string|null
     */
    public function getTargetEntity()
    {
        if ($this->associationMapping) {
            return $this->associationMapping['targetEntity'];
        }

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
        if (!is_array($fieldMapping)) {
            throw new \RuntimeException('The field mapping must be an array');
        }

        $this->fieldMapping = $fieldMapping;

        $this->type        = $this->type ? : $fieldMapping['type'];
        $this->mappingType = $this->mappingType ? : $fieldMapping['type'];
    }

    /**
     * return true if the FieldDescription is linked to an identifier field
     *
     * @return bool
     */
    public function isIdentifier()
    {
        return isset($this->fieldMapping['id']) ? $this->fieldMapping['id'] : false;
    }

    /**
     * set the parent association mappings information
     *
     * @param array $parentAssociationMappings
     */
    public function setParentAssociationMappings(array $parentAssociationMappings)
    {
        foreach ($parentAssociationMappings as $parentAssociationMapping) {
            if (!is_array($parentAssociationMapping)) {
                throw new \RuntimeException('An association mapping must be an array');
            }
        }

        $this->parentAssociationMappings = $parentAssociationMappings;
    }

    /**
     * return the value linked to the description
     *
     * @param  $object
     *
     * @return bool|mixed
     */
    public function getValue($object)
    {
        return $this->getFieldValue($object, $this->getFieldName());
    }
}
