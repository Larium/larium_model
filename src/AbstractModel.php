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

/**
 * AbstractModel class will expose any class properties to setter/getter
 * methods through magic method __call.
 *
 * Also will provide a static factory method for creating new instances.
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
    public static function factory(array $data, array $constrArgs = array())
    {
        $class = get_called_class();
        if ($class === __CLASS__) {
            throw new RuntimeException(sprintf('Cannot instatiate abstract class %s', $class));
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
    public function setData(array $data)
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
