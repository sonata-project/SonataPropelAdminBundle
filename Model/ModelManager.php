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

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\AdminBundle\Model\ModelManagerInterface;

use Sonata\PropelAdminBundle\Admin\FieldDescription;
use Sonata\PropelAdminBundle\Datagrid\ProxyQuery;

use BaseObject;
use BasePeer;
use ModelCriteria;
use Persistent;
use PropelException;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class ModelManager implements ModelManagerInterface
{
    /**
     * Returns a new FieldDescription.
     *
     * The description is filled with the information retrieved from
     * * the respective Peer-class and
     * * the TableMap of the model class.
     *
     * @param string $class   The FQCN of the model.
     * @param string $name    The column name of the model.
     * @param array  $options A list of options to be passed to the new FielDescription.
     *
     * @return FieldDescription
     */
    public function getNewFieldDescriptionInstance($class, $name, array $options = array())
    {
        $fieldDescription = new FieldDescription();
        $fieldDescription->setName($name);
        $fieldDescription->setOptions($options);

        // resolve PEER class
        $peer = constant($class.'::PEER');
        try {
            $columnName = call_user_func_array(array($peer, 'translateFieldName'), array(
                $fieldDescription->getName(),
                BasePeer::TYPE_FIELDNAME,
                BasePeer::TYPE_PHPNAME,
            ));
        } catch (PropelException $e) {
            // The name may not be a column of the model, but an action field or similar.

            // TODO: Figure out how to distinguish between those "special" fields and wrong ones.

            return $fieldDescription;
        }

        /* @var $tableMap \TableMap */
        $tableMap = call_user_func(array($peer, 'getTableMap'));
        $column = $tableMap->getColumnByPhpName($columnName);

        $fieldDescription->setType($column->getType());

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
     * @param string $class
     * @param \Sonata\AdminBundle\Datagrid\ProxyQueryInterface $queryProxy
     *
     * @return void
     */
    public function batchDelete($class, ProxyQueryInterface $queryProxy)
    {
        $queryProxy->delete();
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
            return $model->getPrimaryKey();
        }

        // readonly="true" models
        if ($model instanceof BaseObject && method_exists($model, 'getPrimaryKey')) {
            return $model->getPrimaryKey();
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
}
