<?php
namespace tests\unit {
    use
        mageekguy\atoum,
        Test\Adapter,
        Ftp as TestedClass
    ;

    require_once __DIR__ . '/../../../vendor/autoload.php';

    class Ftp extends atoum\test {
        public function test__construct() {
            $this
                ->if($adapter = new Adapter())
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

namespace Test {
    use
        mageekguy\atoum\test\adapter as AtoumAdapter
    ;

    class Adapter extends AtoumAdapter implements \AdapterInterface
    {
    }
}

namespace {
    interface AdapterInterface
    {
        public function invoke($name, array $args = array());
    }

    class Adapter implements AdapterInterface
    {
        public function invoke($name, array $args = array())
        {
            if (is_callable($name)) {
                return call_user_func_array($name, $args);
            }

            throw new \RuntimeException(sprintf('%s is not callable', var_export($name)));
        }

        public function __call($name, $args)
        {
            return $this->invoke($name, $args);
        }
    }

    class Ftp
    {
        private $adapter;

        public function __construct(AdapterInterface $adapter = null)
        {
            $this->setAdapter($adapter);

            if (false === $this->getAdapter()->extension_loaded('ftp')) {
                throw new \RuntimeException('FTP extension is not loaded');
            }
        }

        public function setAdapter(AdapterInterface $adapter = null)
        {
            $this->adapter = $adapter;

            return $this;
        }

        public function getAdapter()
        {
            if (null === $this->adapter) {
                $this->adapter = new Adapter();
            }

            return $this->adapter;
        }
    }
}