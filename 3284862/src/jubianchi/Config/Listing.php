<?php
namespace jubianchi\Config;

class Listing implements Type {
    const TYPE_NAME = 'listing';
    const CLASS_ATTR = 'class';
    const ITEMS_ATTR = 'items';

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
        return array(static::CLASS_ATTR, static::ITEMS_ATTR);
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
                ->arrayNode(static::ITEMS_ATTR)
                    ->isRequired()
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;
    }
}
