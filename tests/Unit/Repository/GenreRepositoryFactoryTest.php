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

use CoiSA\MovieList\Entity\Builder\GenreBuilder;
use CoiSA\MovieList\Http\Cache\RequestResultCache;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderInterface;
use CoiSA\MovieList\Repository\GenreRepository;
use CoiSA\MovieList\Repository\GenreRepositoryFactory;
use CoiSA\MovieList\Test\Unit\FactoryTestCase;

/**
 * Class GenreRepositoryFactoryTest
 *
 * @package CoiSA\MovieList\Test\Unit\Repository
 */
final class GenreRepositoryFactoryTest extends FactoryTestCase
{
    public function getFactoryObject(): callable
    {
        return new GenreRepositoryFactory();
    }

    public function getReturnedObjectClassName(): string
    {
        return GenreRepository::class;
    }

    public function getDependencyInjectionClassNames(): array
    {
        return [
            TmdbRequestBuilderInterface::class,
            RequestResultCache::class,
            GenreBuilder::class,
        ];
    }
}
