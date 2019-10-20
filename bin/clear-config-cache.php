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

\chdir(__DIR__ . '/../');

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

if (!isset($config['config_cache_path'])) {
    echo 'No configuration cache path found' . PHP_EOL;
    exit(0);
}

if (!\file_exists($config['config_cache_path'])) {
    \printf(
        "Configured config cache file '%s' not found%s",
        $config['config_cache_path'],
        PHP_EOL
    );
    exit(0);
}

if (false === \unlink($config['config_cache_path'])) {
    \printf(
        "Error removing config cache file '%s'%s",
        $config['config_cache_path'],
        PHP_EOL
    );
    exit(1);
}

\printf(
    "Removed configured config cache file '%s'%s",
    $config['config_cache_path'],
    PHP_EOL
);
exit(0);
