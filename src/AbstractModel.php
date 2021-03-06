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

use RuntimeException;
use UnexpectedValueException;
use ReflectionClass;
use ReflectionProperty;

/**
 * AbstractModel class will expose any public or protected class properties
 * to setter/getter methods through magic method __call.
 * This will prevent any biolerplate code to be written for models.
 * AbstractModel will NOT WORK with private properties.
 *
 * Also will provide a static factory method for creating new instances.
 *
 * Any data set either from factory or setData methods, will call setter
 * method for property. If setter method does not exists then will fallback to
 * magic __call method.
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 */
abstract class AbstractModel
{
    /**
     * Factory method for creating new instances.
     *
     * @param array $data       An associative array with data to set.
     * @param array $constrArgs Optional an array with variables to pass to
     *                          class constructor.
     *
     * @throws RuntimeException if factory method called via Abstract class.
     * @return AbstractModel
     */
    final public static function create(array $data, array $constrArgs = array())
    {
        $class = get_called_class();
        if ($class === __CLASS__) {
            throw new RuntimeException(sprintf('Cannot instatiate abstract class %s.', $class));
        }
        $model = empty($constrArgs)
            ? new static()
            : (new ReflectionClass($class))->newInstanceArgs($constrArgs);

        self::setPropetiesValues($data, $model);

        return $model;
    }

    /**
     * Sets data to model.
     * This will call the setter of each property and set the value accordingly.
     *
     * @param array $data
     * @return void
     */
    public function assignData(array $data)
    {
        self::setPropetiesValues($data, $this);
    }

    public function __call($name, $args)
    {
        $type           = substr($name, 0, 3);
        $propertyName   = substr($name, 3);

        if (!($property = $this->propertyExists($propertyName))
            || !in_array($type, array('set', 'get'))
        ) {
            throw new UnexpectedValueException(sprintf('Method with name %s does not exists.', $name));
        }

        $prop = new ReflectionProperty($this, $property);
        if ($prop->isPrivate() || $prop->isStatic()) {
            throw new RuntimeException('Cannot access private or static properties.');
        }

        if ($type === 'set') {
            $this->$property = $args[0];
        }

        return $this->$property;
    }

    private function propertyExists($propertyName)
    {
        $underscore = self::underscore($propertyName);
        $camelcase  = self::camelize($propertyName);

        if (property_exists($this, $underscore)) {
            return $underscore;
        }
        if (property_exists($this, $camelcase)) {
            return $camelcase;
        }

        return false;
    }

    private static function setPropetiesValues($data, $model)
    {
        foreach ($data as $property => $value) {
            $setter = 'set' . self::camelize($property);
            if (is_callable([$model, $setter])) {
                $model->$setter($value);
            }
        }
    }

    private static function camelize($string)
    {
        return lcfirst(str_replace(" ", "", ucwords(strtr($string, "_-", "  "))));
    }

    private static function underscore($string)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $string));
    }
}
