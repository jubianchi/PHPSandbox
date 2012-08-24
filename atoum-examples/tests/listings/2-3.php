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
                ->if($adapter = new atoum\test\adapter())
                ->and($adapter->extension_loaded = true)
                ->then
                    ->object(new TestedClass($adapter))->isInstanceOf('\\Ftp')

                ->if($adapter->extension_loaded = false)
                ->then
                    ->exception(
                        function() use($adapter) {
                            new TestedClass($adapter);
                        }
                    )
                        ->isInstanceOf('\\RuntimeException')
                        ->hasMessage('FTP extension is not loaded')
            ;
        }
    }
}

namespace {
    use
        mageekguy\atoum
    ;

    class Ftp
    {
        private $adapter;

        public function __construct(atoum\adapter $adapter = null)
        {
            $this->setAdapter($adapter);

            if (false === $this->getAdapter()->extension_loaded('ftp')) {
                throw new \RuntimeException('FTP extension is not loaded');
            }
        }

        public function setAdapter(atoum\adapter $adapter = null)
        {
            $this->adapter = $adapter;

            return $this;
        }

        public function getAdapter()
        {
            if (null === $this->adapter) {
                $this->adapter = new atoum\adapter();
            }

            return $this->adapter;
        }
    }
}