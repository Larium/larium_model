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
    public function testShouldCreateModel()
    {
        $foo = FooModel::create(array('bar' => 'bar'));

        $this->assertInstanceOf(
            'Larium\\FooModel',
            $foo
        );
    }

    public function testShouldAccessPropertiesThroughMagicMethodCall()
    {
        $foo = FooModel::create(array(
            'bar' => 'bar',
            'baz' => 'baz'
        ));

        $this->assertEquals('bar', $foo->getBar());
        $this->assertEquals('baz', $foo->getBaz());
    }

    public function testShouldAccessPublicPropertiesThroughMagicMethodCall()
    {
        $foo = FooModel::create(array('rab' => 'rab'));

        $this->assertEquals('rab', $foo->getRab());

        $foo->setRab('foo');

        $this->assertEquals('foo', $foo->getRab());
    }

    public function testShouldAssignPropertiesOfModel()
    {
        $foo = new FooModel();
        $foo->assignData(array(
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
        $foo->assignData(array(
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
    public function testShouldNotCreateAbstractModel()
    {
        AbstractModel::create(array('foo' => 'foo'));
    }

    public function testUserDefinedSetterGetter()
    {
        $foo = new FooModel();
        $foo->setFoo('foo');

        $this->assertEquals('foo.foo', $foo->getFoo());
    }

    public function testCreateWithConstructorArgs()
    {
        $foo = new FooModel();

        $bar = BarModel::create(array(), array($foo));

        $this->assertInstanceOf('Larium\BarModel', $bar);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Cannot access private or static properties.
     */
    public function testShouldnotExposePrivateProperties()
    {
        $foo = new FooModel();

        $foo->getMyPrivate();
    }
}
