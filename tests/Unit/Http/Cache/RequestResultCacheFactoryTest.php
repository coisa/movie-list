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

namespace CoiSA\MovieList\Test\Unit\Http\Cache;

use CoiSA\MovieList\Http\Cache\RequestResultCache;
use CoiSA\MovieList\Http\Cache\RequestResultCacheFactory;
use CoiSA\MovieList\Test\Unit\FactoryTestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class RequestResultCacheFactoryTest
 *
 * @package CoiSA\MovieList\Test\Unit\Http\Cache
 */
final class RequestResultCacheFactoryTest extends FactoryTestCase
{
    public function getFactoryObject(): callable
    {
        return new RequestResultCacheFactory();
    }

    public function getReturnedObjectClassName(): string
    {
        return RequestResultCache::class;
    }

    public function getDependencyInjectionClassNames(): array
    {
        return [
            ClientInterface::class,
            CacheInterface::class,
            LoggerInterface::class,
        ];
    }
}
