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

namespace CoiSA\MovieList\Http\Message;

use Psr\Http\Message\RequestInterface;

/**
 * Interface TmdbRequestBuilderInterface
 *
 * @package CoiSA\MovieList\Http\Message
 */
interface TmdbRequestBuilderInterface
{
    /**
     * @param int $page
     *
     * @return RequestInterface
     */
    public function createMovieUpcomingRequest(int $page = 1): RequestInterface;

    /**
     * @param int $movieId
     *
     * @return RequestInterface
     */
    public function createMovieDetailRequest(int $movieId): RequestInterface;

    /**
     * @return RequestInterface
     */
    public function createMovieGenresRequest(): RequestInterface;

    /**
     * @param string $query
     * @param int    $page
     *
     * @return RequestInterface
     */
    public function createSearchMoviesRequest(string $query, int $page = 1): RequestInterface;
}
