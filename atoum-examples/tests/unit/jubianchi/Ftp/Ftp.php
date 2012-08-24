<?php
namespace tests\unit\jubianchi\Ftp;

use
    mageekguy\atoum,
    jubianchi\Ftp\Ftp as TestedClass
;

require_once __DIR__ . '/../../../../vendor/autoload.php';

class Ftp extends atoum\test
{
    public function test__construct()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->ftp_quit = function() {})
            ->then()
                ->object(new TestedClass($adapter))->isInstanceOf('\\jubianchi\\Ftp\\Ftp')

            ->if($adapter->extension_loaded = false)
            ->then()
                ->exception(
                    function() use($adapter) {
                        new TestedClass($adapter);
                    }
                )
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage('FTP extension is not loaded')
        ;
    }

    public function testGetAdapter()
    {
        $this
            ->if($controller = new \mageekguy\atoum\mock\controller())
            ->and($controller->__construct = function() {})
            ->and($controller->__destruct = function() {})
            ->and($object = new \mock\jubianchi\Ftp\Ftp(null, $controller))
            ->and($object->setAdapter(null))
            ->then()
                ->object($object->getAdapter())->isInstanceOf('\\jubianchi\\Ftp\\Adapter')

            ->if($object->setAdapter($adapter = new \jubianchi\Adapter\Test\Adapter()))
            ->then()
                ->object($object->getAdapter())->isIdenticalTo($adapter)
        ;
    }

    public function testConnect()
    {
        $this
            ->if($controller = new \mageekguy\atoum\mock\controller())
            ->and($controller->__construct = function() {})
            ->and($controller->__destruct = function() {})
            ->and($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->ftp_connect = true)
            ->and($adapter->ftp_login = true)
            ->and($object = new \mock\jubianchi\Ftp\Ftp(null, $controller))
            ->and($object->setAdapter($adapter))
            ->and($object->getMockController()->isConnected = true)
            ->then()
                ->boolean($object->connect(uniqid(), uniqid(), uniqid()))->isTrue()

            ->if($object->getMockController()->isConnected = false)
            ->then()
                ->exception(
                    function() use($object) {
                        $object->connect(uniqid(), uniqid(), uniqid());
                    }
                )
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage('FTP connection has failed')
        ;
    }

    public function testLogin()
    {
        $this
            ->if($controller = new \mageekguy\atoum\mock\controller())
            ->and($controller->__construct = function() {})
            ->and($controller->__destruct = function() {})
            ->and($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->ftp_login = true)
            ->and($object = new \mock\jubianchi\Ftp\Ftp(null, $controller))
            ->and($object->setAdapter($adapter))
            ->then()
                ->boolean($object->login(uniqid(), uniqid()))->isTrue()

            ->if($adapter->ftp_login = false)
            ->then()
                ->exception(
                    function() use($object) {
                        $object->login(uniqid(), uniqid());
                    }
                )
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage('Could not login with the given crednetials')
        ;
    }

    public function testGetConnection()
    {
        $this
            ->if($controller = new \mageekguy\atoum\mock\controller())
            ->and($controller->__construct = function() {})
            ->and($controller->__destruct = function() {})
            ->and($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->ftp_connect = $connection = uniqid())
            ->and($adapter->ftp_login = true)
            ->and($object = new \mock\jubianchi\Ftp\Ftp(null, $controller))
            ->and($object->setAdapter($adapter))
            ->and($object->getMockController()->isConnected = true)
            ->then()
                ->variable($object->getConnection())->isNull()

            ->if($object->connect(uniqid(), uniqid(), uniqid()))
            ->then()
                ->variable($object->getConnection())->isIdenticalTo($connection)
        ;
    }
}