<?php

$script->addTestAllDirectory(__DIR__ . '/tests/unit');

$cliReport = $script->addDefaultReport();
$cliReport->addField(new atoum\report\fields\runner\atoum\logo());
