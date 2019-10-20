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

namespace CoiSA\MovieList\Test\Unit\Entity\Builder;

use CoiSA\MovieList\Entity\Builder\MovieBuilder;
use CoiSA\MovieList\Entity\Builder\MovieBuilderFactory;
use CoiSA\MovieList\Repository\GenreRepositoryInterface;
use CoiSA\MovieList\Test\Unit\FactoryTestCase;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Class MovieBuilderFactoryTest
 *
 * @package CoiSA\MovieList\Test\Unit\Entity\Builder
 */
final class MovieBuilderFactoryTest extends FactoryTestCase
{
    public function getFactoryObject(): callable
    {
        return new MovieBuilderFactory();
    }

    public function getReturnedObjectClassName(): string
    {
        return MovieBuilder::class;
    }

    public function getDependencyInjectionClassNames(): array
    {
        return [
            UriFactoryInterface::class,
            GenreRepositoryInterface::class,
        ];
    }
}
