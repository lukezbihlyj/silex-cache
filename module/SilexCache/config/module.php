<?php

/**
 * Specify application-specific configuration. These settings can be over-ridden
 * by the local environmental settings, so it's safe to specify default values
 * here.
 */
return [
    /**
     * Define how we should cache data with the caching provider. Supported options
     * are: 'array', 'redis'.
     */
    'cache.driver' => 'array',

    /**
     * Optional: Only used with the redis driver, specifies the hostname to connect to.
     */
    'cache.redis' => '127.0.0.1:6379',
];
