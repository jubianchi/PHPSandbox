<?php
namespace Hoa\Praspel\Asserters;

use Hoa\Praspel\Asserter;

class Ensures extends Asserter
{
    public function in($in)
    {
        return $this->getSpecification()->getClause('ensures')->in = $in;
    }
}