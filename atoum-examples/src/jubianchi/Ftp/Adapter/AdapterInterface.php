<?php
namespace jubianchi\Adapter\Ftp;

use
    jubianchi\Adapter\Adapter as BaseAdapter
;

interface AdapterInterface
{
   function extension_loaded($name);
   function is_resource($var);
   function ftp_connect($host, $port = null, $timeout = null);
   function ftp_login($ftp_stream, $username, $password);
   function ftp_quit($ftp);
}