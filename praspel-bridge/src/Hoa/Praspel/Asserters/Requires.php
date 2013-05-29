<?php
namespace Hoa\Praspel\Asserters;

use Hoa\Praspel\Asserter;

class Requires  extends Asserter
{
    public function in($in)
    {
        return $this->getSpecification()->getClause('requires')->in = $in;
    }
}