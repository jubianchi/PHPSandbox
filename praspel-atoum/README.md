## Data generator

```php
<?php
$gen = $this->generator->boundinteger(0, 256);
$bi1 = $gen->generate();
$bi2 = $gen->generate();
$bi3 = $gen->generate();

$si = $this->generator->smallint()->generate();

$int = $this->generator->integer()->generate();
$intOdd = $this->generator->integer()->odd()->generate();
$intEven = $this->generator->integer()->even()->generate();

$bool = $this->generator->boolean()->generate();

$arr = $this->generator->array()
    ->ofSize($this->generator->boundint(1, 10))
    ->keys($this->generator->boundinteger(10, 100))
    ->with($this->generator->boolean)
    ->with($this->generator->smallint)
    ->generate();

$str = $this->generator->expr("regex('[A-Z_\\-è]{5}[0-9]{5}', 10)")->generate()
```

## Data provider

```php
<?php
/**
 * @dataProvider praspelDataProvider
 */
public function test m1 n°3 ( $x, $y ) {
    $this
        ->if($object = new \Sut())
        ->then
            ->boolean($object->m1($x, $y))->isFalse();

    return;
}

public function praspelDataProvider ( ) {
    return $this->generator->provide(
        $this->generator->array()
            ->ofSize(2)
            ->with($this->generator->smallint),
        5
    );
}
```