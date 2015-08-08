<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium;

class BarModel extends AbstractModel
{
    protected $foo;

    public function __construct(FooModel $foo)
    {
        $this->foo = $foo;
    }
}
