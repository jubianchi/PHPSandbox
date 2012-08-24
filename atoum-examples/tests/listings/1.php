<?php
namespace tests\unit {
    use
        mageekguy\atoum,
        Ftp as TestedClass
    ;

    require_once __DIR__ . '/../../vendor/autoload.php';

    class Ftp extends atoum\test {
        public function test__construct() {
            $this
                ->object(new TestedClass())->isInstanceOf('\\Ftp')
            ;
        }
    }
}

namespace {
    class Ftp
    {
        public function __construct()
        {
            if (false === extension_loaded('ftp')) {
                throw new \RuntimeException('FTP extension is not loaded');
            }
        }
    }
}