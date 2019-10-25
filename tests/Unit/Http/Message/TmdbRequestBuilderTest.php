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

namespace CoiSA\MovieList\Test\Unit\Http\Message;

use CoiSA\MovieList\Http\Message\TmdbRequestBuilder;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

final class TmdbRequestBuilderTest extends TestCase
{
    /** @var string */
    private $apiKey;

    /** @var ObjectProphecy|RequestFactoryInterface */
    private $requestFactory;

    private $request;

    private $uri;

    /** @var TmdbRequestBuilder */
    private $builder;

    protected function setUp(): void
    {
        $this->apiKey = \uniqid('apiKey', true);

        $this->requestFactory = $this->prophesize(RequestFactoryInterface::class);
        $this->request        = $this->prophesize(RequestInterface::class);
        $this->uri            = $this->prophesize(UriInterface::class);

        $this->request->getUri()->will([$this->uri, 'reveal']);
        $this->uri->getQuery()->willReturn('api_key=' . $this->apiKey);

        $this->requestFactory->createRequest(
            'GET',
            Argument::type('string')
        )->will([$this->request, 'reveal']);

        $this->builder = new TmdbRequestBuilder(
            $this->apiKey,
            $this->requestFactory->reveal()
        );
    }

    public function testCreateMovieUpcomingRequestShouldReturnRequestWithApiKeyAndGivenPage(): void
    {
        $page    = \random_int(1, 100);
        $request = $this->builder->createMovieUpcomingRequest($page);

        $this->uri->getQuery()->willReturn('api_key=' . $this->apiKey . '&page=' . $page);

        $this->assertInstanceOf(RequestInterface::class, $request);

        $query = $request->getUri()->getQuery();
        \parse_str($query, $parsedQuery);

        $this->assertArrayHasKey('api_key', $parsedQuery);
        $this->assertSame($this->apiKey, $parsedQuery['api_key']);

        $this->assertArrayHasKey('page', $parsedQuery);
        $this->assertEquals($page, $parsedQuery['page']);
    }

    public function testCreateMovieDetailRequestShouldReturnRequestWithApiKeyAndMovieId(): void
    {
        $movieId = \random_int(1, 100);
        $request = $this->builder->createMovieDetailRequest($movieId);

        $this->uri->getPath()->willReturn('/movie/' . $movieId);

        $this->assertInstanceOf(RequestInterface::class, $request);

        $query = $request->getUri()->getQuery();
        \parse_str($query, $parsedQuery);

        $this->assertArrayHasKey('api_key', $parsedQuery);
        $this->assertSame($this->apiKey, $parsedQuery['api_key']);

        $this->assertEquals('/movie/' . $movieId, $request->getUri()->getPath());
    }

    public function testCreateMovieGenresRequestShouldReturnRequestWithApiKey(): void
    {
        $request = $this->builder->createMovieGenresRequest();

        $this->assertInstanceOf(RequestInterface::class, $request);

        $query = $request->getUri()->getQuery();
        \parse_str($query, $parsedQuery);

        $this->assertArrayHasKey('api_key', $parsedQuery);
        $this->assertSame($this->apiKey, $parsedQuery['api_key']);
    }

    public function testCreateSearchMoviesRequestShouldReturnRequestWithApiKeyPageAndQuery(): void
    {
        $query   = \uniqid('query', true);
        $page    = \random_int(1, 100);
        $request = $this->builder->createSearchMoviesRequest($query, $page);

        $this->uri->getQuery()->willReturn('api_key=' . $this->apiKey . '&page=' . $page . '&query=' . $query);

        $this->assertInstanceOf(RequestInterface::class, $request);

        $queryString = $request->getUri()->getQuery();
        \parse_str($queryString, $parsedQuery);

        $this->assertArrayHasKey('api_key', $parsedQuery);
        $this->assertSame($this->apiKey, $parsedQuery['api_key']);

        $this->assertArrayHasKey('page', $parsedQuery);
        $this->assertEquals($page, $parsedQuery['page']);

        $this->assertArrayHasKey('query', $parsedQuery);
        $this->assertEquals($query, $parsedQuery['query']);
    }
}
