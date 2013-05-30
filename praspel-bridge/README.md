```php
<?php
namespace tests\units\Hoa;

use atoum;
use Hoa\Praspel\Test;
use Hoa\Foo as TestedClass;

require_once __DIR__ . DIRECTORY_SEPARATOR . '../../bootstrap.php';

class Foo extends Test
{
    public function test__construct()
    {
        $this
            ->praspel
                ->requires('x')->in(realdom()->boundinteger(0, 256))
                ->requires('y')->in(realdom()->const('foo'))
                ->ensures('\result')->in(realdom()->boolean())
                ->verdict($object = new TestedClass)
            ->praspel
                ->requires('x')->in(realdom()->boundinteger(10, 100))
                ->requires('y')->in(realdom()->const('bar'))
                ->ensures('\result')->in(realdom()->boolean())
                ->verdict($object = new TestedClass)
        ;
    }
}

```
