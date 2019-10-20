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

namespace CoiSA\MovieList\Test\Unit\Repository;

use CoiSA\MovieList\Entity\Builder\MovieBuilder;
use CoiSA\MovieList\Http\Cache\RequestResultCache;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderInterface;
use CoiSA\MovieList\Repository\MovieRepository;
use CoiSA\MovieList\Repository\MovieRepositoryFactory;
use CoiSA\MovieList\Test\Unit\FactoryTestCase;

/**
 * Class MovieRepositoryFactoryTest
 *
 * @package CoiSA\MovieList\Test\Unit\Repository
 */
final class MovieRepositoryFactoryTest extends FactoryTestCase
{
    public function getFactoryObject(): callable
    {
        return new MovieRepositoryFactory();
    }

    public function getReturnedObjectClassName(): string
    {
        return MovieRepository::class;
    }

    public function getDependencyInjectionClassNames(): array
    {
        return [
            TmdbRequestBuilderInterface::class,
            RequestResultCache::class,
            MovieBuilder::class,
        ];
    }
}
