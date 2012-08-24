<?php
namespace jubianchi\Adapter;

use
    jubianchi\Adapter\AdaptableInterface
;

class Adaptable implements AdaptableInterface
{
    /**
     * @var \jubianchi\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @param \jubianchi\Adapter\AdapterInterface $adapter
     *
     * @return Adaptable
     */
    public function setAdapter(AdapterInterface $adapter = null)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @return \jubianchi\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter ?: new Adapter();
    }
}