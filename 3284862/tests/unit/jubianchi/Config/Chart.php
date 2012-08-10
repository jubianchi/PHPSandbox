<?php
namespace tests\unit\jubianchi\Config;

use mageekguy\atoum;
use jubianchi\Config\Chart as TestedClass;

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';
require_once dirname(__DIR__) . '/../../../vendor/mageekguy/atoum/scripts/runner.php';

class Chart extends atoum\test {
    public function testConstants() {
        $this
            ->string(TestedClass::TYPE_NAME)->isEqualTo('chart')
            ->string(TestedClass::CLASS_ATTR)->isEqualTo('class')
            ->string(TestedClass::COLS_ATTR)->isEqualTo('columns')
            ->string(TestedClass::ROWS_ATTR)->isEqualTo('rows')
        ;
    }

    public function testGetType() {
        $this
            ->if($object = new TestedClass())
            ->then
                ->string($object->getType())->isEqualTo(TestedClass::TYPE_NAME)
        ;
    }
}