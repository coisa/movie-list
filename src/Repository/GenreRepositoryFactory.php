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

use CoiSA\MovieList\Entity\Builder\GenreBuilder;
use CoiSA\MovieList\Http\Cache\RequestResultCache;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderInterface;
use Psr\Container\ContainerInterface;

/**
 * Class GenreRepositoryFactory
 *
 * @package CoiSA\MovieList\Repository
 */
final class GenreRepositoryFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return GenreRepository
     */
    public function __invoke(ContainerInterface $container): GenreRepository
    {
        $requestBuilder     = $container->get(TmdbRequestBuilderInterface::class);
        $requestResultCache = $container->get(RequestResultCache::class);
        $genreBuilder       = $container->get(GenreBuilder::class);

        return new GenreRepository($requestBuilder, $requestResultCache, $genreBuilder);
    }
}
