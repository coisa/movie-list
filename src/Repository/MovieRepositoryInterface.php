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

use CoiSA\MovieList\Entity\Movie;

/**
 * Interface MovieRepositoryInterface
 *
 * @package CoiSA\MovieList\Repository
 */
interface MovieRepositoryInterface
{
    /**
     * @return Movie[]
     */
    public function getUpcoming(): iterable;

    /**
     * @param int $movieId
     *
     * @return Movie
     */
    public function getDetail(int $movieId): Movie;

    /**
     * @param string $query
     *
     * @return iterable
     */
    public function search(string $query): iterable;
}
