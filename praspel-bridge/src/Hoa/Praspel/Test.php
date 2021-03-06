<?php
namespace Hoa\Praspel;

use mageekguy\atoum;
use mageekguy\atoum\test\assertion;
use mageekguy\atoum\adapter;
use mageekguy\atoum\annotations;
use mageekguy\atoum\asserter;
use Hoa;
use Hoa\Praspel\Asserters;

class Test extends atoum\test
{
    protected $praspel;

    public function __construct(Asserters\Praspel $praspel = null, adapter $adapter = null, annotations\extractor $annotationExtractor = null, asserter\generator $asserterGenerator = null, assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        $this
            ->setAssertionManager($assertionManager)
            ->setAsserterGenerator($asserterGenerator)
            ->setPraspel($praspel)
        ;

        parent::__construct($adapter, $annotationExtractor, $this->getAsserterGenerator(), $this->getAssertionManager(), $reflectionClassFactory);
    }

    public function setPraspel(Asserters\Praspel $praspel = null)
    {
        $this->praspel = $praspel ?: new Asserters\Praspel();
        $this->praspel->setGenerator($this->getAsserterGenerator());

        return $this;
    }

    public function getPraspel()
    {
        return $this->praspel;
    }

    public function setAssertionManager(assertion\manager $assertionManager = null)
    {
        parent::setAssertionManager($assertionManager);

        $self = $this;

        $this->getAssertionManager()
            ->sethandler('praspel', function() use ($self) { return $self->getPraspel()->reset(); })
            ->sethandler('requires', function($value) use ($self) { return $self->getPraspel()->requires($value); })
            ->sethandler('ensures', function($value) use ($self) { return $self->getPraspel()->ensures($value); })
            ->sethandler('verdict', function() use ($self) { return $self->getPraspel()->verdict(); })
        ;

        return $this;
    }
}
