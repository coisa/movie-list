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

use CoiSA\MovieList\Entity\Builder\EntityBuilderInterface;
use CoiSA\MovieList\Entity\Movie;
use CoiSA\MovieList\Http\Cache\RequestResultCache;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderInterface;
use CoiSA\MovieList\Repository\MovieRepository;
use CoiSA\MovieList\Repository\MovieRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;

/**
 * Class MovieRepositoryTest
 *
 * @package CoiSA\MovieList\Test\Unit\Repository
 */
final class MovieRepositoryTest extends TestCase
{
    /** @var ObjectProphecy|TmdbRequestBuilderInterface */
    private $requestBuilder;

    /** @var ObjectProphecy|RequestResultCache */
    private $requestCache;

    /** @var EntityBuilderInterface|ObjectProphecy */
    private $entityBuilder;

    /** @var ObjectProphecy|RequestInterface */
    private $request;

    /** @var Movie|ObjectProphecy */
    private $movie;

    /** @var MovieRepository */
    private $repository;

    protected function setUp(): void
    {
        $this->requestBuilder = $this->prophesize(TmdbRequestBuilderInterface::class);
        $this->requestCache   = $this->prophesize(RequestResultCache::class);
        $this->entityBuilder  = $this->prophesize(EntityBuilderInterface::class);
        $this->request        = $this->prophesize(RequestInterface::class);
        $this->movie          = $this->prophesize(Movie::class);

        $this->repository = new MovieRepository(
            $this->requestBuilder->reveal(),
            $this->requestCache->reveal(),
            $this->entityBuilder->reveal()
        );
    }

    public function testMovieRepositoryImplementsMovieRepositoryInterface(): void
    {
        $this->assertInstanceOf(MovieRepositoryInterface::class, $this->repository);
    }

    public function testGetDetailWithMovieIdWillReturnExpectedMovie(): void
    {
        $movieId = \random_int(1, 100);
        $data    = [
            'id'   => \random_int(1, 100),
            'name' => \uniqid('name', true)
        ];
        $content = \json_encode($data);

        $this->requestBuilder->createMovieDetailRequest($movieId)->will([$this->request, 'reveal']);
        $this->requestCache->getRequestBodyContent($this->request->reveal())->willReturn($content);
        $this->entityBuilder->createFromArray($data)->will([$this->movie, 'reveal']);

        $this->assertSame($this->movie->reveal(), $this->repository->getDetail($movieId));
    }

    public function testUpcomingWillReturnGenerator(): void
    {
        $data    = [
            'total_pages' => \random_int(1, 100),
            'results'     => []
        ];
        $content = \json_encode($data);

        $this->requestBuilder->createMovieUpcomingRequest()->will([$this->request, 'reveal']);
        $this->requestBuilder->createMovieUpcomingRequest(
            Argument::type('integer')
        )->will([$this->request, 'reveal']);
        $this->requestCache->getRequestBodyContent($this->request->reveal())->willReturn($content);

        $generator = $this->repository->getUpcoming();
        $this->assertInstanceOf(\Generator::class, $generator);
    }

    public function testUpcomingWillRequestForAllAvailablePages(): void
    {
        $data    = [
            'total_pages' => \random_int(1, 100),
            'results'     => []
        ];

        $content = \json_encode($data);

        $this->requestBuilder->createMovieUpcomingRequest()->will([$this->request, 'reveal'])->shouldBeCalledOnce();
        $this->requestBuilder->createMovieUpcomingRequest(
            Argument::type('integer')
        )->will([$this->request, 'reveal'])->shouldBeCalledTimes($data['total_pages']);
        $this->requestCache->getRequestBodyContent($this->request->reveal())->willReturn($content);

        $generator = $this->repository->getUpcoming();
        \iterator_to_array($generator);
    }

    public function testUpcomingWillReturnResultsOfAllPages(): void
    {
        $resultsPerPage = \random_int(1, 20);
        $data           = [
            'total_pages' => \random_int(1, 100),
            'results'     => []
        ];

        for ($i = 0; $i < $resultsPerPage; $i++) {
            $movieData = [
                'id'             => \random_int(1, 100),
                'original_title' => \uniqid('original_title', true),
            ];

            $this->entityBuilder->createFromArray($movieData)->will([$this->movie, 'reveal']);

            $data['results'][] = $movieData;
        }

        $content = \json_encode($data);

        $this->requestBuilder->createMovieUpcomingRequest()->will([$this->request, 'reveal'])->shouldBeCalledOnce();
        $this->requestBuilder->createMovieUpcomingRequest(
            Argument::type('integer')
        )->will([$this->request, 'reveal'])->shouldBeCalledTimes($data['total_pages']);
        $this->requestCache->getRequestBodyContent($this->request->reveal())->willReturn($content);
        $this->entityBuilder->createFromArray(
            Argument::type('array')
        )->shouldBeCalledTimes($data['total_pages'] * $resultsPerPage);

        $generator = $this->repository->getUpcoming();
        $this->assertCount($data['total_pages'] * $resultsPerPage, $generator);
    }

    public function testSerarchWillReturnGenerator(): void
    {
        $query   = \uniqid('query', true);
        $data    = [
            'total_pages' => \random_int(1, 100),
            'results'     => []
        ];
        $content = \json_encode($data);

        $this->requestBuilder->createSearchMoviesRequest($query)->will([$this->request, 'reveal']);
        $this->requestBuilder->createSearchMoviesRequest(
            $query,
            Argument::type('integer')
        )->will([$this->request, 'reveal']);
        $this->requestCache->getRequestBodyContent($this->request->reveal())->willReturn($content);

        $generator = $this->repository->search($query);
        $this->assertInstanceOf(\Generator::class, $generator);
    }

    public function testSearchWillRequestForAllAvailablePages(): void
    {
        $query   = \uniqid('query', true);
        $data    = [
            'total_pages' => \random_int(1, 100),
            'results'     => []
        ];

        $content = \json_encode($data);

        $this->requestBuilder->createSearchMoviesRequest(
            $query
        )->will([$this->request, 'reveal'])->shouldBeCalledOnce();

        $this->requestBuilder->createSearchMoviesRequest(
            $query,
            Argument::type('integer')
        )->will([$this->request, 'reveal'])->shouldBeCalledTimes($data['total_pages']);

        $this->requestCache->getRequestBodyContent($this->request->reveal())->willReturn($content);

        $generator = $this->repository->search($query);
        \iterator_to_array($generator);
    }

    public function testSearchWillReturnResultsOfAllPages(): void
    {
        $query          = \uniqid('query', true);
        $resultsPerPage = \random_int(1, 20);
        $data           = [
            'total_pages' => \random_int(1, 100),
            'results'     => []
        ];

        for ($i = 0; $i < $resultsPerPage; $i++) {
            $movieData = [
                'id'             => \random_int(1, 100),
                'original_title' => \uniqid('original_title', true),
            ];

            $this->entityBuilder->createFromArray($movieData)->will([$this->movie, 'reveal']);

            $data['results'][] = $movieData;
        }

        $content = \json_encode($data);

        $this->requestBuilder->createSearchMoviesRequest(
            $query
        )->will([$this->request, 'reveal'])->shouldBeCalledOnce();
        $this->requestBuilder->createSearchMoviesRequest(
            $query,
            Argument::type('integer')
        )->will([$this->request, 'reveal'])->shouldBeCalledTimes($data['total_pages']);
        $this->requestCache->getRequestBodyContent($this->request->reveal())->willReturn($content);
        $this->entityBuilder->createFromArray(
            Argument::type('array')
        )->shouldBeCalledTimes($data['total_pages'] * $resultsPerPage);

        $generator = $this->repository->search($query);
        $this->assertCount($data['total_pages'] * $resultsPerPage, $generator);
    }
}
