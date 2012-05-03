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

use Sonata\AdminBundle\Model\ModelManagerInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

use Sonata\AdminBundle\Exception\ModelManagerException;

use Sonata\PropelAdminBundle\Admin\FieldDescription;

use ModelCriteria;
use BaseObject;
use Sonata\PropelAdminBundle\Datagrid\ProxyQuery;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class ModelManager implements ModelManagerInterface
{
    /**
     * Returns a new FieldDescription
     *
     * @param string $class
     * @param string $name
     * @param array $options
     *
     * @return \Sonata\AdminBundle\Admin\FieldDescriptionInterface
     */
    public function getNewFieldDescriptionInstance($class, $name, array $options = array())
    {
        $fieldDescription = new FieldDescription();
        $fieldDescription->setName($name);
        $fieldDescription->setOptions($options);

        return $fieldDescription;
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
        // TODO: Implement findBy() method.
    }

    /**
     * @param $class
     * @param array $criteria
     *
     * @return void
     */
    public function findOneBy($class, array $criteria = array())
    {
        // TODO: Implement findOneBy() method.
    }

    /**
     * @param string $class
     * @param int $id
     *
     * @return BaseObject
     */
    public function find($class, $id)
    {
        $queryClass = $class.'Query';

        return $queryClass::create()
            ->findPk($id)
        ;
    }

    /**
     * @param $class
     * @param \Sonata\AdminBundle\Datagrid\ProxyQueryInterface $queryProxy
     *
     * @return void
     */
    public function batchDelete($class, ProxyQueryInterface $queryProxy)
    {
        // TODO: Implement batchDelete() method.
    }

    /**
     * @param  $parentAssociationMapping
     * @param  $class
     *
     * @return void
     */
    public function getParentFieldDescription($parentAssociationMapping, $class)
    {
        // TODO: Implement getParentFieldDescription() method.
    }

    /**
     * @param string $class
     * @param string $alias
     *
     * @return ProxyQueryInterface
     */
    public function createQuery($class, $alias = 'o')
    {
        return new ProxyQuery(new ModelCriteria(null, $class, $alias));
    }

    /**
     * @param string $class
     *
     * @return string
     */
    public function getModelIdentifier($class)
    {
        // TODO: Implement getModelIdentifier() method.

        return 'id';
    }

    /**
     *
     * @param object $model
     *
     * @return array|null
     */
    public function getIdentifierValues($model)
    {
        if ($model instanceof BaseObject && method_exists($model, 'getPrimaryKeys')) {
            return $model->getPrimaryKeys();
        }

        if ($model instanceof \Persistent) {
            return $model->getPrimaryKey();
        }

        // readonly="true" models
        if ($model instanceof BaseObject && method_exists($model, 'getPrimaryKey')) {
            return $model->getPrimaryKey();
        }

        return null;
    }

    /**
     * @param string $class
     *
     * @return array
     */
    public function getIdentifierFieldNames($class)
    {
        // TODO: Implement getIdentifierFieldNames() method.

        return array();
    }

    /**
     * @param BaseObject|null $model
     *
     * @return array|null
     */
    public function getNormalizedIdentifier($model)
    {
        if ($model instanceof BaseObject || $model instanceof Persistent) {
            return $this->getIdentifierValues($model);
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
     * @return void
     */
    public function getModelCollectionInstance($class)
    {
        // TODO: Implement getModelCollectionInstance() method.
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
        // TODO: Implement collectionRemoveElement() method.
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
        // TODO: Implement collectionAddElement() method.
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
        // TODO: Implement collectionHasElement() method.

        return false;
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
        // TODO: Implement collectionClear() method.
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
            '_sort_by' => $this->getModelIdentifier($class),
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
        // TODO: Implement getDataSourceIterator() method.
    }

    /**
     * @param $class
     *
     * @return array
     */
    public function getExportFields($class)
    {
        // TODO: Implement getExportFields() method.

        return array();
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
     * Returns true if the model has a relation
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasMetadata($name)
    {
        return false;
    }

    /**
     * @throws \RuntimeException
     *
     * @param string $name
     *
     * @return void
     */
    public function getMetadata($name)
    {
        throw new \RuntimeException('This ModelManager does not provide MetaData handling.');
    }
}
