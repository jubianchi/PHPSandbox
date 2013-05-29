<?php
namespace Hoa\Praspel\Asserters;

use mageekguy\atoum\asserter;
use Hoa;
use Hoa\Praspel\Model;

class Praspel extends Hoa\Praspel\Asserter
{
    protected $generator;
    protected $specification;

    public function ensures()
    {
        return new Ensures($this->getSpecification(), $this->getGenerator());
    }

    public function requires()
    {
        return new Requires($this->getSpecification(), $this->getGenerator());
    }

    public function verdict($message = null)
    {
        if($this->specification->verdict() === false) {
            $this->fail($message ?: __FUNCTION__ . ' failed!');
        }

        return $this->pass();
    }

    public function reset()
    {
        $this->setSpecification();

        return parent::reset();
    }
}
