<?php
namespace Hoa\Praspel;

use mageekguy\atoum;
use mageekguy\atoum\exceptions;

class Asserter extends atoum\asserter
{
    protected $value;
    protected $isSet;

    public function __construct(Model\Specification $specification = null, atoum\asserter\generator $generator = null)
    {
        $this->specification = $specification ?: new Model\Specification();

        parent::__construct($generator);
    }

    public function getSpecification()
    {
        return $this->specification;
    }

    public function setWith($mixed)
    {
        $this->value = $mixed;
        $this->isSet = true;

        return $this;
    }

    protected function valueIsSet($message = 'Value is undefined')
    {
        if ($this->isSet === false)
        {
            throw new exceptions\logic($message);
        }

        return $this;
    }
}