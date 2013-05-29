```php
$this
    ->praspel
        ->requires('x')->in(realdom()->boundinteger(0, 256))
        ->requires('y')->in(realdom()->const('foo'))
        ->ensures('\result')->in(realdom()->boolean())
        ->verdict()
    ->praspel
        ->requires('x')->in(realdom()->boundinteger(10, 100))
        ->requires('y')->in(realdom()->const('bar'))
        ->ensures('\result')->in(realdom()->boolean())
        ->verdict()
;
```