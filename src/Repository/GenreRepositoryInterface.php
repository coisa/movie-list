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

use CoiSA\MovieList\Entity\Genre;

/**
 * Interface GenreRepositoryInterface
 *
 * @package CoiSA\MovieList\Repository
 */
interface GenreRepositoryInterface
{
    /**
     * @param int $genreId
     *
     * @return null|Genre
     */
    public function getMovieGenre(int $genreId): ?Genre;
}
