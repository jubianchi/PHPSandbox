<?php
namespace jubianchi\Ftp\Adapter;

use
    jubianchi\Adapter\Adapter,
    jubianchi\Ftp\Adapter\FtpAdapterInterface
;

class FtpAdapter extends Adapter implements FtpAdapterInterface
{
    public function extension_loaded($name)
    {
        return extension_loaded($name);
    }

    public function is_resource($var)
    {
        return is_resource($var);
    }

    public function ftp_connect($host, $port = null, $timeout = null)
    {
        return ftp_connect($host, $port, $timeout);
    }

    public function ftp_login($ftp_stream, $username, $password)
    {
        return ftp_login($ftp_stream, $username, $password);
    }

    public function ftp_quit($ftp)
    {
        return ftp_quit($ftp);
    }
}