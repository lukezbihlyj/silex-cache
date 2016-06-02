<?php

namespace LukeZbihlyj\SilexCache;

use LukeZbihlyj\SilexPlus\Application;
use LukeZbihlyj\SilexPlus\ModuleInterface;

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
                    return new RedisCache($app);
                    break;
                default:
                    return new ArrayCache($app);
                    break;
            }
        });
    }
}
