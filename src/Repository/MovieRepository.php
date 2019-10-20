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

use CoiSA\MovieList\Entity\Builder\EntityBuilderInterface;
use CoiSA\MovieList\Entity\Movie;
use CoiSA\MovieList\Http\Cache\RequestResultCache;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Class MovieRepository
 *
 * @package CoiSA\MovieList\Repository
 */
final class MovieRepository implements MovieRepositoryInterface
{
    /** @var TmdbRequestBuilderInterface */
    private $requestBuilder;

    /** @var RequestResultCache */
    private $requestResultCache;

    /** @var EntityBuilderInterface */
    private $movieBuilder;

    /**
     * MovieRepository constructor.
     *
     * @param TmdbRequestBuilderInterface $requestBuilder
     * @param RequestResultCache          $requestResultCache
     * @param EntityBuilderInterface      $movieBuilder
     */
    public function __construct(
        TmdbRequestBuilderInterface $requestBuilder,
        RequestResultCache $requestResultCache,
        EntityBuilderInterface $movieBuilder
    ) {
        $this->requestBuilder     = $requestBuilder;
        $this->requestResultCache = $requestResultCache;
        $this->movieBuilder       = $movieBuilder;
    }

    /**
     * @param int $movieId
     *
     * @return Movie
     */
    public function getDetail(int $movieId): Movie
    {
        $request    = $this->requestBuilder->createMovieDetailRequest($movieId);
        $parsedBody = $this->getDecodedRequestContent($request);

        return $this->movieBuilder->createFromArray($parsedBody);
    }

    /**
     * @return iterable
     */
    public function getUpcoming(): iterable
    {
        $totalPages = $this->getUpcomingTotalPages();

        for ($page = 1; $page <= $totalPages; $page++) {
            yield from $this->getUpcomingPage($page);
        }
    }

    /**
     * @param string $query
     *
     * @return iterable
     */
    public function search(string $query): iterable
    {
        $totalPages = $this->getSearchTotalPages($query);

        for ($page = 1; $page <= $totalPages; $page++) {
            yield from $this->getSearchPage($query, $page);
        }
    }

    /**
     * @return int
     */
    private function getUpcomingTotalPages(): int
    {
        $request    = $this->requestBuilder->createMovieUpcomingRequest();
        $parsedBody = $this->getDecodedRequestContent($request);

        return $parsedBody['total_pages'];
    }

    /**
     * @param int $page
     *
     * @return iterable
     */
    private function getUpcomingPage(int $page = 1): iterable
    {
        $request    = $this->requestBuilder->createMovieUpcomingRequest($page);
        $parsedBody = $this->getDecodedRequestContent($request);
        $results    = $parsedBody['results'];

        foreach ($results as $result) {
            yield $this->movieBuilder->createFromArray($result);
        }
    }

    /**
     * @param string $query
     *
     * @return int
     */
    private function getSearchTotalPages(string $query): int
    {
        $request    = $this->requestBuilder->createSearchMoviesRequest($query);
        $parsedBody = $this->getDecodedRequestContent($request);

        return $parsedBody['total_pages'];
    }

    /**
     * @param string $query
     * @param int    $page
     *
     * @return iterable
     */
    private function getSearchPage(string $query, int $page = 1): iterable
    {
        $request    = $this->requestBuilder->createSearchMoviesRequest($query, $page);
        $parsedBody = $this->getDecodedRequestContent($request);
        $results    = $parsedBody['results'];

        foreach ($results as $result) {
            yield $this->movieBuilder->createFromArray($result);
        }
    }

    /**
     * @param RequestInterface $request
     *
     * @return array
     */
    private function getDecodedRequestContent(RequestInterface $request): array
    {
        $content = $this->requestResultCache->getRequestBodyContent($request);

        return \json_decode($content, true);
    }
}
