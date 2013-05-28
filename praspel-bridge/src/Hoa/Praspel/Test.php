<?php
namespace Hoa\Praspel;

use mageekguy\atoum;
use mageekguy\atoum\test\assertion;
use mageekguy\atoum\adapter;
use mageekguy\atoum\annotations;
use mageekguy\atoum\asserter;
use Hoa;

class Test extends atoum\test
{
    protected $praspel;

    public function __construct(Hoa\Praspel $praspel = null, adapter $adapter = null, annotations\extractor $annotationExtractor = null, asserter\generator $asserterGenerator = null, assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        $this
            ->setPraspel($praspel);
        ;

        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);
    }

    public function setPraspel(Hoa\Praspel $praspel = null)
    {
        $this->praspel = $praspel ?: new Hoa\Praspel();

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
        $praspel = $this->getPraspel();

        $this->getAssertionManager()
            ->sethandler('praspel', function() use ($praspel) { return $praspel; })
            ->sethandler('predicate', function() use ($self) { $self->praspel->doStuff(); return $this; })
        ;

        return $this;
    }
}