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

namespace CoiSA\MovieList\Http\Cache;

use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class RequestResultCacheFactory
 *
 * @package CoiSA\MovieList\Http\Cache
 */
final class RequestResultCacheFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return RequestResultCache
     */
    public function __invoke(ContainerInterface $container): RequestResultCache
    {
        $client = $container->get(ClientInterface::class);
        $cache  = $container->get(CacheInterface::class);
        $logger = $container->get(LoggerInterface::class);

        return new RequestResultCache($client, $cache, $logger);
    }
}
