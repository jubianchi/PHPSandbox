<?php

$script->addTestAllDirectory(__DIR__ . '/tests/unit');

$script->addDefaultReport();

list($cliReport) = $script->getReports();
$cliReport->addField(new atoum\report\fields\runner\atoum\logo());
