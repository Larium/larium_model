<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * This file is part of the Larium Model package.
 *
 * (c) Andreas Kollaros <andreas@larium.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Larium;

class AbstractModelTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldFactoryModel()
    {
        $foo = FooModel::factory(array('bar' => 'bar'));

        $this->assertInstanceOf(
            'Larium\\FooModel',
            $foo
        );
    }

    public function testShouldAccessPropertiesThroughMagicMethodCall()
    {
        $foo = FooModel::factory(array(
            'bar' => 'bar',
            'baz' => 'baz'
        ));

        $this->assertEquals('bar', $foo->getBar());
        $this->assertEquals('baz', $foo->getBaz());
    }

    public function testShouldAccessPublicPropertiesThroughMagicMethodCall()
    {
        $foo = FooModel::factory(array('rab' => 'rab'));

        $this->assertEquals('rab', $foo->getRab());

        $foo->setRab('foo');

        $this->assertEquals('foo', $foo->getRab());
    }

    public function testShouldAssignPropertiesOfModel()
    {
        $foo = new FooModel();
        $foo->setData(array(
            'bar' => 'bar',
            'baz' => 'baz',
            'rab' => 'foo'
        ));

        $this->assertEquals('bar', $foo->getBar());
        $this->assertEquals('baz', $foo->getBaz());
        $this->assertEquals('foo', $foo->getRab());
    }

    public function testShouldAssignPropertiesForVariousCases()
    {
        $foo = new FooModel();
        $foo->setData(array(
            'firstName' => 'John',
            'last_name' => 'Doe'
        ));

        $this->assertEquals('John', $foo->getFirstName());
        $this->assertEquals('Doe', $foo->getLastName());
    }

    /**
     * @expectedException UnexpectedValueException
     * @expectedExceptionMessage Method with name getTestName does not exists.
     */
    public function testShouldNotCallUndefinedProperties()
    {
        $foo = new FooModel();

        $foo->getTestName();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Cannot instatiate abstract class Larium\AbstractModel.
     */
    public function testShouldNotFactoryAbstractModel()
    {
        AbstractModel::factory(array('foo' => 'foo'));
    }

    public function testUserDefinedSetterGetter()
    {
        $foo = new FooModel();
        $foo->setFoo('foo');

        $this->assertEquals('foo.foo', $foo->getFoo());
    }
}
