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

namespace CoiSA\MovieList;

use CoiSA\MovieList\Entity\Builder\GenreBuilder;
use CoiSA\MovieList\Entity\Builder\MovieBuilder;
use CoiSA\MovieList\Entity\Builder\MovieBuilderFactory;
use CoiSA\MovieList\Http\Cache\RequestResultCache;
use CoiSA\MovieList\Http\Cache\RequestResultCacheFactory;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilder;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderFactory;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderInterface;
use CoiSA\MovieList\Http\RequestHandler\DetailRequestHandler;
use CoiSA\MovieList\Http\RequestHandler\DetailRequestHandlerFactory;
use CoiSA\MovieList\Http\RequestHandler\IndexRequestHandler;
use CoiSA\MovieList\Http\RequestHandler\IndexRequestHandlerFactory;
use CoiSA\MovieList\Http\RequestHandler\SearchRequestHandler;
use CoiSA\MovieList\Http\RequestHandler\SearchRequestHandlerFactory;
use CoiSA\MovieList\Repository\GenreRepository;
use CoiSA\MovieList\Repository\GenreRepositoryFactory;
use CoiSA\MovieList\Repository\GenreRepositoryInterface;
use CoiSA\MovieList\Repository\MovieRepository;
use CoiSA\MovieList\Repository\MovieRepositoryFactory;
use CoiSA\MovieList\Repository\MovieRepositoryInterface;

/**
 * Class ConfigProvider
 *
 * @package CoiSA\MovieList
 */
final class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            'aliases'    => $this->getAliases(),
            'invokables' => $this->getInvokables(),
            'factories'  => $this->getFactories(),
        ];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [
            TmdbRequestBuilderInterface::class => TmdbRequestBuilder::class,
            MovieRepositoryInterface::class    => MovieRepository::class,
            GenreRepositoryInterface::class    => GenreRepository::class,
        ];
    }

    /**
     * @return array
     */
    public function getInvokables(): array
    {
        return [
            GenreBuilder::class => GenreBuilder::class,
        ];
    }

    /**
     * @return array
     */
    public function getFactories(): array
    {
        return [
            IndexRequestHandler::class  => IndexRequestHandlerFactory::class,
            DetailRequestHandler::class => DetailRequestHandlerFactory::class,
            SearchRequestHandler::class => SearchRequestHandlerFactory::class,
            RequestResultCache::class   => RequestResultCacheFactory::class,
            MovieRepository::class      => MovieRepositoryFactory::class,
            GenreRepository::class      => GenreRepositoryFactory::class,
            TmdbRequestBuilder::class   => TmdbRequestBuilderFactory::class,
            MovieBuilder::class         => MovieBuilderFactory::class,
        ];
    }
}
