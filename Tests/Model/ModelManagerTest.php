<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Tests\Model;

use Sonata\PropelAdminBundle\Admin\FieldDescription;
use Sonata\PropelAdminBundle\Model\ModelManager;

/**
 * ModelManager tests
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ModelManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCollectionCreate()
    {
        $manager = new ModelManager();
        $collection = $manager->getModelCollectionInstance('\DateTime');

        $this->assertInstanceOf('\PropelObjectCollection', $collection);
        $this->assertSame('\DateTime', $collection->getModel());
    }

    public function testCollectionClearWhenAlreadyEmpty()
    {
        $manager = new ModelManager();
        $collection = new \PropelObjectCollection();

        $this->assertSame(array(), $manager->collectionClear($collection));
        $this->assertTrue($collection->isEmpty());
    }

    public function testCollectionClear()
    {
        $manager = new ModelManager();
        $object = new \stdClass;
        $object->foo = 42;
        $collection = new \PropelObjectCollection();
        $collection->append($object);

        $this->assertSame(array(
            $object
        ), $manager->collectionClear($collection));
        $this->assertTrue($collection->isEmpty());
    }

    public function testCollectionAdd()
    {
        $manager = new ModelManager();
        $collection = new \PropelObjectCollection();

        $object = new \stdClass;
        $object->foo = 42;

        $this->assertTrue($collection->isEmpty());

        $manager->collectionAddElement($collection, $object);
        $this->assertSame(array(
            $object
        ), $collection->getArrayCopy());

        $this->assertCount(1, $collection);
    }

    public function testCollectionHas()
    {
        $manager = new ModelManager();

        $object = new \stdClass;
        $object->foo = 42;

        $otherObject = new \stdClass;
        $otherObject->bar = 'baz';

        $collection = new \PropelObjectCollection();
        $collection->append($object);

        $this->assertTrue($manager->collectionHasElement($collection, $object));
        $this->assertFalse($manager->collectionHasElement($collection, $otherObject));
    }

    public function testCollectionRemove()
    {
        $manager = new ModelManager();

        $object = new \stdClass;
        $object->foo = 42;

        $collection = new \PropelObjectCollection();
        $collection->append($object);

        $this->assertSame(array(
            $object
        ), $collection->getArrayCopy());

        $manager->collectionRemoveElement($collection, $object);

        $this->assertTrue($collection->isEmpty());
    }

    public function testCollectionRemoveDoesNothingWhenObjectIsNotFound()
    {
        $manager = new ModelManager();

        $object = new \stdClass;
        $object->foo = 42;

        $otherObject = new \stdClass;
        $otherObject->bar = 'baz';

        $collection = new \PropelObjectCollection();
        $collection->append($object);

        $this->assertSame(array(
            $object
        ), $collection->getArrayCopy());

        $manager->collectionRemoveElement($collection, $otherObject);

        $this->assertSame(array(
            $object
        ), $collection->getArrayCopy());
    }

    public function testGetDataSourceIterator()
    {
        $fields = array('title' => '[title]');
        $firstResult = 10;
        $maxResults = 25;
        $data = array(
            array('id' => 42, 'title' => 'Super!'),
            array('id' => 24, 'title' => 'Foo'),
        );
        $results = new \PropelCollection();
        $results->setData($data);

        // configure the query mock
        $query = $this->getMock('Sonata\AdminBundle\Datagrid\ProxyQueryInterface');

        $query->expects($this->once())
            ->method('setFirstResult')
            ->with($this->equalTo($firstResult));

        $query->expects($this->once())
            ->method('setMaxResults')
            ->with($this->equalTo($maxResults));

        $query->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($results));

        // configure the datagrid mock
        $datagrid = $this->getMockBuilder('Sonata\PropelAdminBundle\Datagrid\Datagrid')
            ->disableOriginalConstructor()
            ->setMethods(array('buildPager', 'getQuery'))
            ->getMock();

        $datagrid->expects($this->once())
               ->method('buildPager');

        $datagrid->expects($this->once())
                 ->method('getQuery')
                 ->will($this->returnValue($query));

        // create the manager
        $manager = new ModelManager();

        // and finally test it!
        $collectionIterator = $manager->getDataSourceIterator($datagrid, $fields, $firstResult, $maxResults);
        $this->assertInstanceOf('\Exporter\Source\PropelCollectionSourceIterator', $collectionIterator);
        $this->assertSame(array(
            array('title' => 'Super!'),
            array('title' => 'Foo'),
        ), iterator_to_array($collectionIterator));
    }

    /**
     * @dataProvider getIdentifierValuesDataProvider
     */
    public function testGetIdentifierValues($modelClass, $pkValues, $expectedValues)
    {
        $model = $this->getMock($modelClass);
        $model->expects($this->once())
            ->method('getPrimaryKey')
            ->will($this->returnValue($pkValues));

        $manager = new ModelManager();
        $this->assertSame($expectedValues, $manager->getIdentifierValues($model));
    }

    public function testGetIdentifierValuesWithInvalidModel()
    {
        $model = $this->getMock('\SplStack');
        $model->expects($this->never())
            ->method('getPrimaryKey');

        $manager = new ModelManager();
        $this->assertNull($manager->getIdentifierValues($model));
    }

    public function getIdentifierValuesDataProvider()
    {
        $baseObjectMock = '\Sonata\PropelAdminBundle\Tests\Model\BaseObjectMock';

        return array(
            // modelClass,          pkValues,       expectedValues
            array('\Persistent',    42,             array(42)),
            array('\Persistent',    array(24, 42),  array(24, 42)),

            array($baseObjectMock,  42,             array(42)),
            array($baseObjectMock,  array(24, 42),  array(24, 42)),
        );
    }

    /**
     * @dataProvider getNormalizedIdentifierDataProvider
     */
    public function testGetNormalizedIdentifier($modelClass, $pkValues, $expectedValues)
    {
        $model = $this->getMock($modelClass);
        $model->expects($this->once())
            ->method('getPrimaryKey')
            ->will($this->returnValue($pkValues));

        $manager = new ModelManager();
        $this->assertSame($expectedValues, $manager->getNormalizedIdentifier($model));
    }

    public function getNormalizedIdentifierDataProvider()
    {
        $baseObjectMock = '\Sonata\PropelAdminBundle\Tests\Model\BaseObjectMock';

        return array(
            // modelClass,          pkValues,       expectedValues
            array('\Persistent',    42,             '42'),
            array('\Persistent',    array(24, 42),  '24~42'),

            array($baseObjectMock,  42,             '42'),
            array($baseObjectMock,  array(24, 42),  '24~42'),

            array($baseObjectMock,  array(),        null),
            array($baseObjectMock,  '',             null),
            array($baseObjectMock,  0,              null),
        );
    }

    /**
     * @dataProvider                invalidFieldNameProvider
     * @expectedException           \RuntimeException
     * @expectedExceptionMessage    The name argument must be a string
     */
    public function testGetNewFieldDescriptionInstanceWithInvalidFieldName($fieldName)
    {
        $manager = new ModelManager();
        $manager->getNewFieldDescriptionInstance('\Dummy\Classname', $fieldName);
    }

    public function invalidFieldNameProvider()
    {
        return array(
            array(null),
            array(1),
            array(array()),
            array(new \stdClass),
        );
    }

    /**
     * @dataProvider validFieldNameProvider
     */
    public function testGetNewFieldDescriptionInstanceWithValidFieldName($tableMap, $columnsMap, $className, $fieldName, $options, array $expected)
    {
        $manager = new TestableModelManager();
        $manager->addTable($className, $tableMap);
        foreach ($columnsMap as $map) {
            $manager->addColumn($className, $map->getName(), $map);
        }

        $fieldDescription = $manager->getNewFieldDescriptionInstance($className, $fieldName, $options);

        $this->assertInstanceOf('\Sonata\PropelAdminBundle\Admin\FieldDescription', $fieldDescription);
        $this->assertSame($fieldName, $fieldDescription->getName());
        $this->assertSame($expected['type'], $fieldDescription->getType());
        $this->assertEquals(array_merge(array('placeholder' => 'short_object_description_placeholder', 'link_parameters' => array()), $options), $fieldDescription->getOptions());
        $this->assertEquals($expected['association_mapping'], $fieldDescription->getAssociationMapping());
        $this->assertEquals($expected['field_mapping'], $fieldDescription->getFieldMapping());
    }

    /**
     * @dataProvider translatableFieldNamesProvider
     */
    public function testTranslateFieldNameWithValidData($modelManager, $className, $fieldName, $expectedPhpName)
    {
        $phpName = $modelManager->translateFieldName($className, $fieldName);

        $this->assertSame($expectedPhpName, $phpName);
    }

    public function translatableFieldNamesProvider()
    {
        $dbMap = new \DatabaseMap('default');

        $authorTableMap = new \TableMap();
        $authorTableMap->setName('author');
        $authorTableMap->setPhpName('Author');
        $authorTableMap->setClassname('Acme\\DemoBundle\\Model\\Author');
        $authorTableMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $dbMap->addTableObject($authorTableMap);

        $bookTableMap = new \TableMap();
        $bookTableMap->setName('book');
        $bookTableMap->setPhpName('Book');
        $bookTableMap->setClassname('Acme\\DemoBundle\\Model\\Book');
        $bookTableMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $bookTableMap->addColumn('NAME', 'Name', 'VARCHAR', false, 255, null);
        $bookTableMap->addColumn('SLUG', 'Slug', 'VARCHAR', false, 255, null);
        $bookTableMap->addForeignKey('AUTHOR_ID', 'AuthorId', 'INTEGER', 'author', 'id', true, null, null);
        $dbMap->addTableObject($bookTableMap);

        $bookTableMap->addRelation('Author', 'Acme\\DemoBundle\\Model\\Author', \RelationMap::MANY_TO_ONE, array('AUTHOR_ID' => 'ID', ), 'CASCADE', null);

        $manager = new TestableModelManager();
        $manager->addTable('Acme\\DemoBundle\\Model\\Author', $authorTableMap);
        $manager->addTable('Acme\\DemoBundle\\Model\\Book', $bookTableMap);

        return array(
            array($manager, 'Acme\\DemoBundle\\Model\\Author', 'ID', 'Id'),
            array($manager, 'Acme\\DemoBundle\\Model\\Author', 'Id', 'Id'),
            array($manager, 'Acme\\DemoBundle\\Model\\Author', 'id', 'Id'),

            array($manager, 'Acme\\DemoBundle\\Model\\Book', 'name', 'Name'),

            array($manager, 'Acme\\DemoBundle\\Model\\Book', 'author', 'AuthorId'),
            array($manager, 'Acme\\DemoBundle\\Model\\Book', 'Author', 'AuthorId'),
        );
    }

    public function validFieldNameProvider()
    {
        $className = '\Foo\Book';
        $options = array(
            'foo' => 'bar',
        );

        // table maps
        $emptyTableMap = new \TableMap();
        $authorTable = new \TableMap();
        $authorTable->setClassName('\Foo\Author');
        $resellerTable = new \TableMap();
        $resellerTable->setClassName('\Foo\Reseller');
        $relationsTableMap = $this->getMock('\TableMap');

        // relations
        $mainAuthorRelation = new \RelationMap('MainAuthor');
        $mainAuthorRelation->setType(\RelationMap::MANY_TO_ONE);
        $mainAuthorRelation->setForeignTable($authorTable);

        $authorRelation = new \RelationMap('Author');
        $authorRelation->setType(\RelationMap::ONE_TO_MANY);
        $authorRelation->setForeignTable($authorTable);

        $resellerRelation = new \RelationMap('Reseller');
        $resellerRelation->setType(\RelationMap::MANY_TO_MANY);
        $resellerRelation->setLocalTable($resellerTable);

        // configure table maps mocks
        $relationsTableMap->expects($this->any())
            ->method('getRelations')
            ->will($this->returnValue(array($mainAuthorRelation, $authorRelation, $resellerRelation)));

        // columns
        $titleColumn = new \ColumnMap('Title', $emptyTableMap);
        $titleColumn->setType('text');
        $titleColumn->setPhpName('Title');

        $titleFieldMapping = array('id' => false, 'type' => 'text', 'fieldName' => 'Title');

        return array(
            //    tableMap,             columns,                className,    fieldName,    options, expected
            // no error is thrown if the column is not found
            array(null,                 array(),                $className,   'Title',      $options, array('type' => null, 'association_mapping' => null, 'field_mapping' => null)),
            // column not found, type remains empty
            array($emptyTableMap,       array(),                $className,   'Title',      $options, array('type' => null, 'association_mapping' => null, 'field_mapping' => null)),
            // column found, type filled
            array($emptyTableMap,       array($titleColumn),    $className,   'Title',      $options, array('type' => 'text', 'association_mapping' => null, 'field_mapping' => $titleFieldMapping)),
            // test relations
            array($relationsTableMap,   array($titleColumn),    $className,   'MainAuthor', $options, array('type' => \RelationMap::MANY_TO_ONE, 'association_mapping' => array('targetEntity' => '\Foo\Author', 'type' => \RelationMap::MANY_TO_ONE), 'field_mapping' => null)),
            array($relationsTableMap,   array($titleColumn),    $className,   'Authors',    $options, array('type' => \RelationMap::ONE_TO_MANY, 'association_mapping' => array('targetEntity' => '\Foo\Author', 'type' => \RelationMap::ONE_TO_MANY), 'field_mapping' => null)),
            array($relationsTableMap,   array($titleColumn),    $className,   'Resellers',  $options, array('type' => \RelationMap::MANY_TO_MANY, 'association_mapping' => array('targetEntity' => '\Foo\Reseller', 'type' => \RelationMap::MANY_TO_MANY), 'field_mapping' => null)),
        );
    }

    public function testGetDefaultSortValues()
    {
        $manager = new TestableModelManager();
        $manager->modelIdentifier = 'Id';

        $this->assertSame(array(
            '_sort_order'   => 'ASC',
            '_sort_by'      => 'Id',
            '_page'         => 1,
        ), $manager->getDefaultSortValues('foo'));
    }

    /**
     * @dataProvider testGetSortParametersProvider
     */
    public function testGetSortParameters($field, $values, $expectedSortParameters)
    {
        $manager = new ModelManager();
        $datagrid = $this->getMock('\Sonata\AdminBundle\Datagrid\DatagridInterface');
        $datagrid
            ->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue($values));

        $this->assertSame(array('filter' => $expectedSortParameters), $manager->getSortParameters($field, $datagrid));
    }

    public function testGetSortParametersProvider()
    {
        $field = new FieldDescription();
        $field->setName('slug');

        $slugSortField = new FieldDescription();
        $slugSortField->setName('slug');

        $slugSortFieldWithSortable = new FieldDescription();
        $slugSortFieldWithSortable->setName('SuperSlug');
        $slugSortFieldWithSortable->setOption('sortable', 'slug');

        $titleSortField = new FieldDescription();
        $titleSortField->setName('title');

        return array(
            array( $field, array('_sort_by' => $slugSortField, '_sort_order' => 'ASC'), array('_sort_by' => 'slug', '_sort_order' => 'DESC') ),
            array( $field, array('_sort_by' => $slugSortField, '_sort_order' => 'DESC'), array('_sort_by' => 'slug', '_sort_order' => 'ASC') ),
            array( $field, array('_sort_by' => $titleSortField, '_sort_order' => 'ASC'), array('_sort_by' => 'slug', '_sort_order' => 'ASC') ),
            array( $field, array('_sort_by' => $slugSortFieldWithSortable, '_sort_order' => 'DESC'), array('_sort_by' => 'slug', '_sort_order' => 'ASC') ),
        );
    }

    public function testGetPaginationParameters()
    {
        $field = new FieldDescription();
        $field->setName('slug');

        $datagrid = $this->getMock('\Sonata\AdminBundle\Datagrid\DatagridInterface');
        $datagrid
            ->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array('_sort_by' => $field)));

        $manager = new ModelManager();

        $this->assertSame(array(
            'filter' => array(
                '_sort_by'  => 'slug',
                '_page'     => 42,
            ),
        ), $manager->getPaginationParameters($datagrid, 42));
    }
}

/**
 * @codeCoverageIgnore
 */
class BaseObjectMock extends \BaseObject
{
    public function getPrimaryKey()
    {
    }
}

/**
 * @codeCoverageIgnore
 */
class TestableModelManager extends ModelManager
{
    public $modelIdentifier;

    public function addTable($class, $tableMap)
    {
        $this->cache[$class] = $tableMap;
    }

    public function addColumn($class, $property, $columnMap)
    {
        $this->cache[$class.'::'.$property] = $columnMap;
    }

    public function getModelIdentifier($class)
    {
        if ($this->modelIdentifier !== null) {
            return $this->modelIdentifier;
        }

        return parent::getModelIdentifier($class);
    }
}
