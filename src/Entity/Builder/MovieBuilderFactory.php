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

namespace CoiSA\MovieList\Entity\Builder;

use CoiSA\MovieList\Repository\GenreRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Class MovieBuilderFactory
 *
 * @package CoiSA\MovieList\Entity\Builder
 */
final class MovieBuilderFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return MovieBuilder
     */
    public function __invoke(ContainerInterface $container): MovieBuilder
    {
        $uriFactory      = $container->get(UriFactoryInterface::class);
        $genreRespotiroy = $container->get(GenreRepositoryInterface::class);

        return new MovieBuilder($uriFactory, $genreRespotiroy);
    }
}
