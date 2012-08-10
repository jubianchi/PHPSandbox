<?php
namespace jubianchi\Config;

interface Type {
    /**
     * @static
     * @abstract
     *
     * @return string
     */
    static function getType();

    /**
     * @static
     * @abstract
     *
     * @return array
     */
    static function getFields();

    /**
     * @static
     * @abstract
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $root
     */
    static function build(\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $root);
}
