<?php

namespace LukeZbihlyj\SilexCache\Cache;

/**
 * @package LukeZbihlyj\SilexCache\Cache\CacheInterface
 */
interface CacheInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $value
     * @param integer $ttl
     * @return void
     */
    public function set($key, $value, $ttl);

    /**
     * @param string $key
     * @return void
     */
    public function delete($key);

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key);
}
