<?php
use jubianchi\Config\Configuration,
    jubianchi\Config\Table,
    jubianchi\Config\Listing,
    jubianchi\Config\Chart;

require_once __DIR__ . '/vendor/autoload.php';

$config = array(
    'layouts' => array(
        'tableau' => array(
            Configuration::TYPE_ATTR => Table::TYPE_NAME,
            Table::CLASS_ATTR => 'formated_table',
            Table::ROWS_ATTR => array('measures'),
            Table::COLS_ATTR => array('dim1', 'dim2')
        ),
        'liste' => array(
            Configuration::TYPE_ATTR => Listing::TYPE_NAME,
            Listing::CLASS_ATTR => 'formated_table',
            Listing::ITEMS_ATTR => array('measures')
        ),
        'graphique' => array(
            Configuration::TYPE_ATTR => Chart::TYPE_NAME,
            Chart::CLASS_ATTR => 'formated_table',
            Chart::ROWS_ATTR => array('measures'),
            Chart::COLS_ATTR => array('dim1', 'dim2')
        )
    )
);

$validator = new \jubianchi\Config\Validator();
$validator->validate($config, new \jubianchi\Config\TypeFinder());
