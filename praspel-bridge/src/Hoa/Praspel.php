<?php
namespace Hoa;

use mageekguy\atoum\asserter;
use Hoa;
use Hoa\Praspel\Model;
use Hoa\Praspel\Asserters;

class Praspel
{
    protected $generator;
    protected $specification;

    public function __construct(asserter\generator $generator = null)
    {
        $this->setGenerator($generator);
        $this->setSpecification();
    }

    public function setGenerator(asserter\generator $generator = null)
    {
        $this->generator = $generator ?: new asserter\generator();
        $this->locale = $this->generator->getLocale();

        return $this;
    }

    public function getGenerator()
    {
        return $this->generator;
    }

    public function ensures()
    {
        return new Hoa\Praspel\Asserters\Ensures($this->getSpecification(), $this->getGenerator());
    }

    public function requires()
    {
        return new Hoa\Praspel\Asserters\Requires($this->getSpecification(), $this->getGenerator());
    }

    public function verdict($message = null)
    {
        if($this->specification->verdict() === false) {
            throw new \mageekguy\atoum\asserter\exception($message ?: __FUNCTION__ . ' failed!');
        }

        return $this->getGenerator();
    }

    public function reset()
    {
        return $this->setSpecification();
    }

    public function setSpecification(Model\Specification $specification = null)
    {
        $this->specification = $specification ?: new Model\Specification();

        return $this;
    }

    public function getSpecification()
    {
        return $this->specification;
    }
}
