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

use PropelColumnTypes;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class FieldDescription extends BaseFieldDescription
{
    /**
     * A mapping between PropelColumnTypes and field types.
     *
     * This mapping is data type based, it does not account foreign keys or such.
     *
     * @todo Finish this list and add their respective filters.
     * @todo What about the binary fields, does it make sense to search within binary data?
     *
     * @var array
     */
    static protected $typeMap = array(
        // integer
        PropelColumnTypes::INTEGER => 'propel_number',
        PropelColumnTypes::BIGINT => 'propel_number',
        PropelColumnTypes::SMALLINT => 'propel_number',
        PropelColumnTypes::TINYINT => 'propel_number',

        // double
        PropelColumnTypes::NUMERIC => 'propel_number',
        PropelColumnTypes::DECIMAL => 'propel_number',
        PropelColumnTypes::REAL => 'propel_number',
        PropelColumnTypes::FLOAT => 'propel_number',
        PropelColumnTypes::DOUBLE => 'propel_number',

        // string
        PropelColumnTypes::CHAR => 'propel_string',
        PropelColumnTypes::VARCHAR => 'propel_string',
        PropelColumnTypes::LONGVARCHAR => 'propel_string',

        // blob and binary
        PropelColumnTypes::CLOB => 'propel_string',
        PropelColumnTypes::CLOB_EMU => 'propel_string',
        PropelColumnTypes::BLOB => 'propel_string',
        PropelColumnTypes::BINARY => 'propel_string',
        PropelColumnTypes::VARBINARY => 'propel_string',
        PropelColumnTypes::LONGVARBINARY => 'propel_string',
    );

    /**
     * Set the type of this field.
     *
     * Transforms PropelColumnTypes based on the type map.
     *
     * @see self::$typeMap
     *
     * @param string $type
     */
    public function setType($type)
    {
        if (isset(static::$typeMap[$type])) {
            $type = static::$typeMap[$type];
        }

        parent::setType($type);
    }

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

    /**
     * set the parent association mappings information
     *
     * @param array $parentAssociationMappings
     *
     * @return void
     */
    public function setParentAssociationMappings(array $parentAssociationMappings)
    {
        // TODO: Implement setParentAssociationMappings() method.
    }

    /**
     * return the value linked to the description
     *
     * @todo Add handling of related values.
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
