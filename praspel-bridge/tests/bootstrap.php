<?php
namespace Hoa\Praspel\Model {
    class Specification {
        public function verdict() {}
        public function getClause() { return new \StdClass; }
    }
}

namespace {
    require_once implode(
        DIRECTORY_SEPARATOR,
        array(
            __DIR__,
            '..',
            'vendor',
            'autoload.php'
        )
    );

    require_once implode(
        DIRECTORY_SEPARATOR,
        array(
            __DIR__,
            '..',
            'vendor',
            'atoum',
            'atoum',
            'scripts',
            'runner.php'
        )
    );
}
