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
use CoiSA\MovieList\Http\Message\TmdbRequestBuilder;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderFactory;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderInterface;

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
            TmdbRequestBuilder::class   => TmdbRequestBuilderFactory::class,
            MovieBuilder::class         => MovieBuilderFactory::class,
        ];
    }
}
