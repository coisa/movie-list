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

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Class TmdbRequestBuilder
 *
 * @package CoiSA\MovieList\Http\Message
 */
final class TmdbRequestBuilder implements TmdbRequestBuilderInterface
{
    /** @const string */
    private const API_BASE_URL = 'https://api.themoviedb.org/3';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * TmdbRequestBuilder constructor.
     *
     * @param string                  $apiKey
     * @param RequestFactoryInterface $requestFactory
     */
    public function __construct(
        string $apiKey,
        RequestFactoryInterface $requestFactory
    ) {
        $this->apiKey         = $apiKey;
        $this->requestFactory = $requestFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function createMovieUpcomingRequest(int $page = 1): RequestInterface
    {
        $uri = $this->createResourceUrl('/movie/upcoming', \compact('page'));

        return $this->requestFactory->createRequest('GET', $uri);
    }

    /**
     * {@inheritDoc}
     */
    public function createMovieDetailRequest(int $movieId): RequestInterface
    {
        $uri = $this->createResourceUrl('/movie/' . $movieId);

        return $this->requestFactory->createRequest('GET', $uri);
    }

    /**
     * {@inheritDoc}
     */
    public function createMovieGenresRequest(): RequestInterface
    {
        $uri = $this->createResourceUrl('/genre/movie/list');

        return $this->requestFactory->createRequest('GET', $uri);
    }

    /**
     * @param string $query
     * @param int    $page
     *
     * @return RequestInterface
     */
    public function createSearchMoviesRequest(string $query, int $page = 1): RequestInterface
    {
        $uri = $this->createResourceUrl('/search/movie', \compact('query', 'page'));

        return $this->requestFactory->createRequest('GET', $uri);
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    private function createResourceUrl(string $resource, array $params = []): string
    {
        $params['api_key'] = $this->apiKey;

        return \sprintf(
            '%s%s?%s',
            self::API_BASE_URL,
            $resource,
            \http_build_query($params)
        );
    }
}
