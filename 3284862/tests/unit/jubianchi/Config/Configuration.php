<?php
namespace tests\unit\jubianchi\Config;

use mageekguy\atoum;
use jubianchi\Config\Configuration as TestedClass;

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';
require_once dirname(__DIR__) . '/../../../vendor/mageekguy/atoum/scripts/runner.php';

class Configuration extends atoum\test {
    public function testConstants() {
        $this
            ->string(TestedClass::ROOT_NAME)->isEqualTo('layouts')
            ->string(TestedClass::TYPE_ATTR)->isEqualTo('type')
        ;
    }
}