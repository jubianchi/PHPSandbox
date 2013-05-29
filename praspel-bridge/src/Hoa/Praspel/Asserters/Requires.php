<?php
namespace Hoa\Praspel\Asserters;

use Hoa\Praspel\Asserter;

class Requires  extends Asserter
{
    public function in($in)
    {
        $this->getSpecification()->getClause('requires')->in = $in;

        return $this->pass();
    }
}
