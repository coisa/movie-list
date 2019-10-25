<?php

/**
 * This file is part of coisa/movie-list.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/movie-list
 * @copyright Copyright (c) 2019 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

// To enable or disable caching, set the `ConfigAggregator::ENABLE_CACHE` boolean in
// `config/autoload/local.php`.
$cacheConfig = [
    ConfigAggregator::ENABLE_CACHE => false,
    'config_cache_path'            => 'data/cache/config-cache.php',
];

$aggregator = new ConfigAggregator([
    \CoiSA\Monolog\ConfigProvider::class,
    \Zend\Expressive\ConfigProvider::class,
    \Zend\Expressive\Helper\ConfigProvider::class,
    \Zend\Expressive\Plates\ConfigProvider::class,
    \Zend\Expressive\Router\ConfigProvider::class,
    \Zend\Expressive\Router\FastRouteRouter\ConfigProvider::class,
    \Zend\HttpHandlerRunner\ConfigProvider::class,

    \CoiSA\MovieList\ConfigProvider::class,

    new ArrayProvider($cacheConfig),
    new PhpFileProvider(\realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php')
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
