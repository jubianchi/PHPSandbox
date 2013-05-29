<?php
namespace Hoa\Praspel\Asserters;

use Hoa\Praspel\Asserter;

class Ensures extends Asserter
{
    public function in($in)
    {
        $this->getSpecification()->getClause('ensures')->in = $in;

        return $this->pass();
    }
}
