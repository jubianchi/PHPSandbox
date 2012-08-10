<?php
namespace jubianchi\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException,
    jubianchi\Config\TypeFinder;

class Validator {
    /** @var \Symfony\Component\Config\Definition\Builder\TreeBuilder */
    private $builder;

    /** @var  \Symfony\Component\Config\Definition\Builder\NodeDefinition */
    private $root;

    /** @var \Symfony\Component\Config\Definition\Builder\NodeDefinition */
    private $prototype;

    /**
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getNewBuilder() {
        return new TreeBuilder();
    }

    /**
     * @return \Symfony\Component\Config\Definition\Processor
     */
    public function getNewProcessor() {
        return new Processor();
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getBuilder() {
        if(null === $this->builder) {
            $this->builder = $this->getNewBuilder();
        }

        return $this->builder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    public function getRoot() {
        if(null === $this->root) {
            $this->root = $this->getBuilder()->root(Configuration::ROOT_NAME);
        }

        return $this->root;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     */
    public function getPrototype() {
        if(null === $this->prototype) {
            $this->prototype = $this->getRoot()->prototype('array');
        }

        return $this->prototype;
    }

    /**
     * @param TypeFinder $finder
     * @throws \RuntimeException
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    protected function build(TypeFinder $finder) {
        $prototype = $this->getPrototype();

        try {
            $prototype
                ->children()
                    ->enumNode(Configuration::TYPE_ATTR)->values($finder->getValidTypes())->end()
                ->end()
            ;
        } catch(\InvalidArgumentException $exception) {
            throw new \RuntimeException('No valid types found', $exception->getCode(), $exception);
        }

        foreach($finder->getValidFields() as $field) {
            $prototype->append(new \Symfony\Component\Config\Definition\Builder\VariableNodeDefinition($field));
        }

        $self = $this;
        $prototype
            ->validate()
                ->ifTrue(function($v) { return is_array($v); })
                ->then(function($v) use($self, $finder) {
                    $builder = $self->getNewBuilder();

                    $root = $builder->root($v[Configuration::TYPE_ATTR]);
                    $root->children()->scalarNode(Configuration::TYPE_ATTR)->isRequired()->end();

                    $validator = $finder->getClassFromType($v[Configuration::TYPE_ATTR]);
                    if(null === $validator) {
                        throw new InvalidConfigurationException(
                            sprintf(
                                'No validator defined for type "%s"',
                                $v[Configuration::TYPE_ATTR]
                            )
                        );
                    }

                    $validator::build($root);
                    $self->process(array($v[Configuration::TYPE_ATTR] => $v), $builder);
                })
            ->end()
        ;
    }

    /**
     * @param array $config
     * @param TypeFinder $finder
     */
    public function validate(array $config, TypeFinder $finder) {
        $this->build($finder);

        $this->process($config, $this->getBuilder());
    }

    /**
     * @param array $config
     * @param \Symfony\Component\Config\Definition\Builder\TreeBuilder $builder
     */
    public function process(array $config, TreeBuilder $builder) {
        $this->getNewProcessor()->process($builder->buildTree(), $config);
    }
}
