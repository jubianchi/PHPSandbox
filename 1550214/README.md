## PHP-CLI & XDebug

#### Debug your PHP-CLI scripts on demand with XDebug

### How to install

To install, you only have to run the install.sh shell script :

```shell
$ ./install.sh <target_path=/usr/local/bin>

# target_path : where to put the xdebug executable (default : /usr/local/bin)
```

**Before being able to fully use this executable, you'll have to install the PHP Xdebug extension and configure your IDE or debug client.**

### How to use

You can use this executable to debug PHP-Cli scripts, be they standalone scripts or Symfony (1 / 2) tasks.

```shell
$ xdebug my_standalone_script.php

$ xdebug ./symfony namespace:command
```
