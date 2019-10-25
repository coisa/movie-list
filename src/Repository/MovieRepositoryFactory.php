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

namespace CoiSA\MovieList\Repository;

use CoiSA\MovieList\Entity\Builder\MovieBuilder;
use CoiSA\MovieList\Http\Cache\RequestResultCache;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderInterface;
use Psr\Container\ContainerInterface;

/**
 * Class MovieRepositoryFactory
 *
 * @package CoiSA\MovieList\Repository
 */
final class MovieRepositoryFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return MovieRepository
     */
    public function __invoke(ContainerInterface $container): MovieRepository
    {
        $requestBuilder     = $container->get(TmdbRequestBuilderInterface::class);
        $requestResultCache = $container->get(RequestResultCache::class);
        $movieFactory       = $container->get(MovieBuilder::class);

        return new MovieRepository($requestBuilder, $requestResultCache, $movieFactory);
    }
}
