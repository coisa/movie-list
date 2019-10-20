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

use Cache\Adapter\Filesystem\FilesystemCachePool;
use Cache\Adapter\PHPArray\ArrayCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpClient\Psr18Client;
use Zend\Diactoros\RequestFactory;
use Zend\Diactoros\UriFactory;

return [
    Local::class => [
        'root' => __DIR__ . '/../../data'
    ],
    'dependencies' => [
        'aliases' => [
            CacheInterface::class          => FilesystemCachePool::class,
            ClientInterface::class         => Psr18Client::class,
            RequestFactoryInterface::class => RequestFactory::class,
            UriFactoryInterface::class     => UriFactory::class,
            AdapterInterface::class        => Local::class,
        ],
        'invokables' => [
            NullLogger::class     => NullLogger::class,
            RequestFactory::class => RequestFactory::class,
            Psr18Client::class    => Psr18Client::class,
            ArrayCachePool::class => ArrayCachePool::class,
            UriFactory::class     => UriFactory::class,
        ],
        'factories' => [
            Filesystem::class => function (ContainerInterface $container) {
                $adapter = $container->get(AdapterInterface::class);

                return new Filesystem($adapter);
            },
            Local::class => function (ContainerInterface $container) {
                $config = $container->get('config');

                return new Local($config[Local::class]['root']);
            },
            FilesystemCachePool::class => function (ContainerInterface $container) {
                $filesystem = $container->get(Filesystem::class);

                return new FilesystemCachePool($filesystem);
            }
        ]
    ]
];
