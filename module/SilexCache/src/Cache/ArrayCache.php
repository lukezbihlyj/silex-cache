<?php

namespace LukeZbihlyj\SilexCache\Cache;

/**
 * @package LukeZbihlyj\SilexCache\Cache\ArrayCache
 */
class ArrayCache implements CacheInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $ttl = false)
    {
        $this->data[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        unset($this->data[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        return isset($this->data[$key]);
    }
}
