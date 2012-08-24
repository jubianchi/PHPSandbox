<?php
namespace jubianchi\Ftp;

use
    jubianchi\Adapter\Adaptable,
    jubianchi\Adapter\AdapterInterface
;

class Ftp extends Adaptable
{
    /**
     * @var resource
     */
    private $connection;

    /**
     * @param \jubianchi\Adapter\AdapterInterface $adapter
     *
     * @throws \RuntimeException
     */
    public function __construct(AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        if (false === $this->getAdapter()->extension_loaded('ftp')) {
            throw new \RuntimeException('FTP extension is not loaded');
        }
    }

    /**
     * @return \jubianchi\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter ?: new Adapter();
    }

    /**
     * @param string $host
     * @param string $login
     * @param string $password
     * @param int    $port
     * @param int    $timeout
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    public function connect($host, $login, $password, $port = 21, $timeout = 90)
    {
        $this->connection = $this->getAdapter()->ftp_connect($host, $port, $timeout);

        if (false === $this->isConnected()) {
            throw new \RuntimeException('FTP connection has failed');
        }

        try {
            $this->login($login, $password);
        } catch (\RuntimeException $exc) {
            $this->connection = null;

            throw $exc;
        }

        return true;
    }

    /**
     * @param string $login
     * @param string $password
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    public function login($login, $password)
    {
        if (false === $this->getAdapter()->ftp_login($this->getConnection(), $login, $password)) {
            throw new \RuntimeException('Could not login with the given crednetials');
        }

        return true;
    }

    /**
     * @return resource
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return (true === $this->getAdapter()->is_resource($this->getConnection()));
    }

    public function __destruct()
    {
        $this->getAdapter()->ftp_quit($this->getConnection());
    }
}