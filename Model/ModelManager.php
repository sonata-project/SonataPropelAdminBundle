<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Model;

use Exporter\Source\PropelCollectionSourceIterator;

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Model\ModelManagerInterface;

use Sonata\PropelAdminBundle\Admin\FieldDescription;
use Sonata\PropelAdminBundle\Datagrid\ProxyQuery;

use BaseObject;
use ModelCriteria;
use Persistent;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class ModelManager implements ModelManagerInterface
{
    const ID_SEPARATOR = '~';

    /**
     * @var array
     */
    protected $cache = array();

    /**
     * Returns a new FieldDescription.
     *
     * The description is filled with the information retrieved from
     * * the respective Peer-class and
     * * the TableMap of the model class.
     *
     * @param string $class   The FQCN of the model.
     * @param string $name    The column name of the model (should be given as a phpName).
     * @param array  $options A list of options to be passed to the new FielDescription.
     *
     * @return FieldDescription
     */
    public function getNewFieldDescriptionInstance($class, $name, array $options = array())
    {
        if (!is_string($name)) {
            throw new \RunTimeException('The name argument must be a string');
        }

        $fieldDescription = new FieldDescription();
        $fieldDescription->setName($name);
        $fieldDescription->setOptions($options);
        $fieldDescription->setParentAssociationMappings($this->getParentAssociationMappings($class, $name));

        if (!$table = $this->getTable($class)) {
            return $fieldDescription;
            // TODO should we throw a logic exception here ?
        }

        foreach ($table->getRelations() as $relation) {
            if (in_array($relation->getType(), array(\RelationMap::MANY_TO_ONE, \RelationMap::ONE_TO_MANY))) {
                if (strtolower($name) === strtolower($relation->getName())) {
                    $fieldDescription->setAssociationMapping(array(
                        'targetEntity'  => $relation->getForeignTable()->getClassName(),
                        'type'          => $relation->getType()
                    ));
                }
            } elseif ($relation->getType() === \RelationMap::MANY_TO_MANY) {
                if (strtolower($name) === strtolower($relation->getPluralName())) {
                    $fieldDescription->setAssociationMapping(array(
                        'targetEntity'  => $relation->getLocalTable()->getClassName(),
                        'type'          => $relation->getType()
                    ));
                }
            }
        }

        if ($column = $this->getColumn($class, $name)) {
            $fieldDescription->setType($column->getType());
        }

        return $fieldDescription;
    }

    /**
     * @param type $fieldDescription
     * @param type $class
     */
    public function getParentAssociationMappings($baseClass, $propertyFullName)
    {
        $nameElements = explode('.', $propertyFullName);
        array_pop($nameElements);
        $parentAssociationMappings = array();

        foreach ($nameElements as $nameElement) {
            $parentAssociationMappings[] = array(
                'fieldName' => $nameElement
            );
        }

        return $parentAssociationMappings;
    }

    /**
     * @param BaseObject $object
     *
     * @return void
     */
    public function create($object)
    {
        $object->save();
    }

    /**
     * @param BaseObject $object
     *
     * @return void
     */
    public function update($object)
    {
        $object->save();
    }

    /**
     * @param BaseObject $object
     *
     * @return void
     */
    public function delete($object)
    {
        $object->delete();
    }

    /**
     * @param string $class
     * @param array $criteria
     *
     * @return object
     */
    public function findBy($class, array $criteria = array())
    {
        $query = $this->createQuery($class);

        // TODO: handle critierias
        return $query->find();
    }

    /**
     * @param $class
     * @param array $criteria
     *
     * @return void
     */
    public function findOneBy($class, array $criteria = array())
    {
        $query = $this->createQuery($class);

        // TODO: handle critierias
        return $query->findOne();
    }

    /**
     * @param string $class
     * @param int $id
     *
     * @return BaseObject
     */
    public function find($class, $id)
    {
        if ($this->hasCompositePk($class)) {
            $id = explode(self::ID_SEPARATOR, $id);
        }

        return $this->createQuery($class)->findPk($id);
    }

    /**
     * @param string $class
     * @param \Sonata\AdminBundle\Datagrid\ProxyQueryInterface $queryProxy
     *
     * @return void
     */
    public function batchDelete($class, ProxyQueryInterface $queryProxy)
    {
        if (count($queryProxy->getQuery()->getMap()) == 0) {
            $queryProxy->deleteAll();
        } else {
            $queryProxy->delete();
        }
    }

    /**
     * @param  $parentAssociationMapping
     * @param  $class
     */
    public function getParentFieldDescription($parentAssociationMapping, $class)
    {
        $fieldName = $parentAssociationMapping['fieldName'];

        $metadata = $this->getMetadata($class);

        $associatingMapping = $metadata->associationMappings[$parentAssociationMapping];

        $fieldDescription = $this->getNewFieldDescriptionInstance($class, $fieldName);
        $fieldDescription->setName($parentAssociationMapping);
        $fieldDescription->setAssociationMapping($associatingMapping);

        return $fieldDescription;
    }

    /**
     * @param string $class
     * @param string $alias
     *
     * @return ProxyQueryInterface
     */
    public function createQuery($class, $alias = 'o')
    {
        $queryClass = $class . 'Query';
        return new ProxyQuery($queryClass::create($alias));
    }

    /**
     * @todo Add handling of multi-column primary keys.
     *
     * @param string $class
     *
     * @return string|null
     */
    public function getModelIdentifier($class)
    {
        $fieldNames = $this->getIdentifierFieldNames($class);

        if (1 === count($fieldNames)) {
            return $fieldNames[0];
        }

        return null;
    }

    /**
     *
     * @param BaseObject|Persistent $model
     *
     * @return array|null
     */
    public function getIdentifierValues($model)
    {
        if ($model instanceof Persistent) {
            // if an array is returned (composite PK), nothing is done.
            // otherwise we return an array with only one element: the identifier
            return (array) $model->getPrimaryKey();
        }

        // readonly="true" models
        if ($model instanceof BaseObject && method_exists($model, 'getPrimaryKey')) {
            return (array) $model->getPrimaryKey();
        }

        return null;
    }

    /**
     * Return a list of all field names that qualify to be an identifier.
     *
     * The list will contain:
     * * any single-column primary key
     * * any single-column unique index
     * * any auto_increment column
     *
     * @todo Add retrieving of described identifiers other than simple PK.
     *
     * @param string $class The FQCN of the model.
     *
     * @return string[]
     */
    public function getIdentifierFieldNames($class)
    {
        $fieldNames = array();

        $peer = constant($class.'::PEER');

        /* @var $tableMap \TableMap */
        $tableMap = call_user_func(array($peer, 'getTableMap'));
        foreach ($tableMap->getPrimaryKeys() as $eachColumn) {
            $fieldNames[] = $eachColumn->getPhpName();
        }

        return $fieldNames;
    }

    /**
     * @param BaseObject|null $model
     *
     * @return array|null
     */
    public function getNormalizedIdentifier($model)
    {
        if ($model instanceof BaseObject || $model instanceof Persistent) {
            $values = $this->getIdentifierValues($model);

            return implode(self::ID_SEPARATOR, $values);
        }

        return null;
    }

    /**
     * @param string $class
     *
     * @return BaseObject
     */
    public function getModelInstance($class)
    {
        return new $class;
    }

    /**
     *
     * @param string $class
     *
     * @return \PropelObjectCollection
     */
    public function getModelCollectionInstance($class)
    {
        $collection = new \PropelObjectCollection();
        $collection->setModel($class);

        return $collection;
    }

    /**
     * Removes an element from the collection
     *
     * @param mixed $collection
     * @param mixed $element
     *
     * @return void
     */
    public function collectionRemoveElement(&$collection, &$element)
    {
        $index = $collection->search($element);
        if ($index !== false) {
            $collection->remove($index);
        }
    }

    /**
     * Add an element from the collection
     *
     * @param mixed $collection
     * @param mixed $element
     *
     * @return mixed
     */
    public function collectionAddElement(&$collection, &$element)
    {
        $collection->append($element);
    }

    /**
     * Check if the element exists in the collection
     *
     * @param mixed $collection
     * @param mixed $element
     *
     * @return boolean
     */
    public function collectionHasElement(&$collection, &$element)
    {
        return $collection->contains($element);
    }

    /**
     * Clear the collection
     *
     * @param mixed $collection
     *
     * @return mixed
     */
    public function collectionClear(&$collection)
    {
        return $collection->clear();
    }

    /**
     * Returns the parameters used in the columns header
     *
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface $fieldDescription
     * @param \Sonata\AdminBundle\Datagrid\DatagridInterface $datagrid
     *
     * @return array
     */
    public function getSortParameters(FieldDescriptionInterface $fieldDescription, DatagridInterface $datagrid)
    {
        // TODO: Implement getSortParameters() method.

        return array();
    }

    /**
     * @param string $class
     *
     * @return array
     */
    public function getDefaultSortValues($class)
    {
        return array(
            '_sort_order' => 'ASC',
            '_sort_by' => null,
            '_page' => 1,
        );
    }

    /**
     * @param string $class
     * @param array $array
     *
     * @return void
     */
    public function modelReverseTransform($class, array $array = array())
    {
        // TODO: Implement modelReverseTransform() method.
    }

    /**
     * @param string $class
     * @param object $instance
     *
     * @return void
     */
    public function modelTransform($class, $instance)
    {
        // TODO: Implement modelTransform() method.
    }

    /**
     * @param mixed $query
     *
     * @return void
     */
    public function executeQuery($query)
    {
        // TODO: Implement executeQuery() method.
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridInterface $datagrid
     * @param array $fields
     * @param null $firstResult
     * @param null $maxResult
     *
     * @return void
     */
    public function getDataSourceIterator(DatagridInterface $datagrid, array $fields, $firstResult = null, $maxResult = null)
    {
        $datagrid->buildPager();
        $query = clone $datagrid->getQuery();

        $query->distinct();
        $query->setFirstResult($firstResult);
        $query->setMaxResults($maxResult);

        return new PropelCollectionSourceIterator($query->execute(), $fields);
    }

    /**
     * @param $class
     *
     * @return array
     */
    public function getExportFields($class)
    {
        $fields = array();
        foreach ($this->getTable($class)->getColumns() as $column) {
            $fields[] = $column->getPhpName();
        }

        return $fields;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridInterface $datagrid
     * @param int $page
     *
     * @return array
     */
    public function getPaginationParameters(DatagridInterface $datagrid, $page)
    {
        // TODO: Implement getPaginationParameters() method.

        return array();
    }

    /**
     * @todo Add support for related classes.
     * @todo Add support for multi-column primary key.
     *
     * @param string $class
     * @param \Sonata\AdminBundle\Datagrid\ProxyQueryInterface $query
     * @param array $idx
     *
     * @return void
     */
    public function addIdentifiersToQuery($class, ProxyQueryInterface $query, array $idx)
    {
        if (null !== $column = $this->getModelIdentifier($class)) {
            $query->filterBy($column, $idx, \Criteria::IN);
        }
    }

    /**
     * {@inheritDoc}
     *
     * The ORM implementation does nothing special but you still should use
     * this method when using the id in a URL to allow for future improvements.
     */
    public function getUrlsafeIdentifier($entity)
    {
        return $this->getNormalizedIdentifier($entity);
    }

    /**
     * @param string $class
     *
     * @return \TableMap
     */
    protected function getTable($class)
    {
        if (isset($this->cache[$class])) {
            return $this->cache[$class];
        }

        if (class_exists($queryClass = $class.'Query')) {
            $query = new $queryClass();

            return $this->cache[$class] = $query->getTableMap();
        }
    }

    /**
     * @param string $class
     * @param string $property
     *
     * @return \ColumnMap
     */
    protected function getColumn($class, $property)
    {
        if (isset($this->cache[$class.'::'.$property])) {
            return $this->cache[$class.'::'.$property];
        }

        $table = $this->getTable($class);

        if ($table && $table->hasColumn($property)) {
            return $this->cache[$class.'::'.$property] = $table->getColumn($property);
        }

        if ($table && $table->hasColumnByInsensitiveCase($property)) {
            return $this->cache[$class.'::'.$property] = $table->getColumnByInsensitiveCase($property);
        }
    }

    /**
     * Indicates if the given class has a composite primary key.
     *
     * @param string $class
     *
     * @return boolean
     */
    protected function hasCompositePk($class)
    {
        return count($this->getIdentifierFieldNames($class)) !== 1;
    }
}
