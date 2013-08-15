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
}
