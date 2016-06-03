<?php

namespace LukeZbihlyj\SilexCache;

use LukeZbihlyj\SilexPlus\Application;
use LukeZbihlyj\SilexPlus\ModuleInterface;
use LukeZbihlyj\SilexCache\Cache;

/**
 * @package LukeZbihlyj\SilexCache\Module
 */
class Module implements ModuleInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigFile()
    {
        return __DIR__ . '/../config/module.php';
    }

    /**
     * {@inheritDoc}
     */
    public function init(Application $app)
    {
        $app['cache'] = $app->share(function() use ($app) {
            switch ($app['cache.driver']) {
                case 'redis':
                    return new Cache\RedisCache($app);
                    break;
                default:
                    return new Cache\ArrayCache($app);
                    break;
            }
        });
    }
}
