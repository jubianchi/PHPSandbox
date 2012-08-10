<?php
namespace jubianchi\Config;

class Table implements Type {
    const TYPE_NAME = 'table';
    const CLASS_ATTR = 'class';
    const ROWS_ATTR = 'rows';
    const COLS_ATTR = 'columns';

    /**
     * @static
     *
     * @return string
     */
    public static function getType() {
        return static::TYPE_NAME;
    }

    /**
     * @static
     *
     * @return array
     */
    public static function getFields() {
        return array(static::CLASS_ATTR, static::ROWS_ATTR, static::COLS_ATTR);
    }

    /**
     * @static
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $root
     */
    public static function build(\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $root) {
        $root
            ->children()
                ->scalarNode(static::CLASS_ATTR)->isRequired()->end()
                ->arrayNode(static::ROWS_ATTR)
                    ->isRequired()
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode(static::COLS_ATTR)
                    ->isRequired()
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;
    }
}
