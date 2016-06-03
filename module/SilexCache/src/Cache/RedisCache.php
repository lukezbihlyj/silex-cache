<?php

namespace LukeZbihlyj\SilexCache\Cache;

use InvalidArgumentException;
use LukeZbihlyj\SilexPlus\Application;
use Credis_Client;
use Credis_Cluster;

/**
 * @package LukeZbihlyj\SilexCache\Cache\RedisCache
 */
class RedisCache implements CacheInterface
{
    const DEFAULT_HOST = 'localhost';
    const DEFAULT_PORT = 6379;
    const DEFAULT_DATABASE = 0;
    const KEY_PREFIX = 'app:';

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Credis_Client
     */
    protected $driver;

    /**
     * @param Application $app
     * @return self
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        if (is_array($this->app['session.redis'])) {
            $this->driver = new Credis_Cluster($this->app['session.redis']);
        } else {
            list($host, $port, $dsnDatabase, $user, $password, $options) = $this->parseDsn($this->app['session.redis']);

            $timeout = isset($options['timeout']) ? intval($options['timeout']) : null;
            $persistent = isset($options['persistent']) ? $options['persistent'] : '';

            $this->driver = new Credis_Client($host, $port, $timeout, $persistent);

            if ($password) {
                $this->driver->auth($password);
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        $key = self::KEY_PREFIX . $key;

        return unserialize($this->driver->get($key));
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $ttl = false)
    {
        $key = self::KEY_PREFIX . $key;

        $this->driver->set($key, serialize($value));

        if ($ttl) {
            $this->driver->expire($key, $ttl);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $key = self::KEY_PREFIX . $key;

        $this->driver->del($key);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        $key = self::KEY_PREFIX . $key;

        return $this->driver->exists($key) === 1;
    }

    /**
     * @param string $dsn
     * @return array
     */
    protected function parseDsn($dsn)
    {
        if ($dsn == '') {
            $dsn = 'redis://' . self::DEFAULT_HOST;
        }

        $parts = parse_url($dsn);
        $validSchemes = ['redis', 'tcp'];

        if (isset($parts['scheme']) && ! in_array($parts['scheme'], $validSchemes)) {
            throw new InvalidArgumentException('Invalid DSN. Supported schemes are ' . implode(', ', $validSchemes));
        }

        if (!isset($parts['host']) && isset($parts['path'])) {
            $parts['host'] = $parts['path'];
            unset($parts['path']);
        }

        $port = isset($parts['port']) ? intval($parts['port']) : self::DEFAULT_PORT;
        $database = false;

        if (isset($parts['path'])) {
            $database = intval(preg_replace('/[^0-9]/', '', $parts['path']));
        }

        $user = isset($parts['user']) ? $parts['user'] : false;
        $pass = isset($parts['pass']) ? $parts['pass'] : false;
        $options = [];

        if (isset($parts['query'])) {
            parse_str($parts['query'], $options);
        }

        return [$parts['host'], $port, $database, $user, $pass, $options];
    }
}
