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
use CoiSA\MovieList\Entity\Genre;
use CoiSA\MovieList\Http\Cache\RequestResultCache;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderInterface;
use CoiSA\MovieList\Repository\GenreRepository;
use CoiSA\MovieList\Repository\GenreRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;

/**
 * Class GenreRepositoryTest
 *
 * @package CoiSA\MovieList\Test\Unit\Repository
 */
final class GenreRepositoryTest extends TestCase
{
    /** @var ObjectProphecy|TmdbRequestBuilderInterface */
    private $requestBuilder;

    /** @var ObjectProphecy|RequestResultCache */
    private $requestCache;

    /** @var EntityBuilderInterface|ObjectProphecy */
    private $entityBuilder;

    /** @var ObjectProphecy|RequestInterface */
    private $request;

    /** @var Genre|ObjectProphecy */
    private $genre;

    /** @var GenreRepository */
    private $repository;

    protected function setUp(): void
    {
        $this->requestBuilder = $this->prophesize(TmdbRequestBuilderInterface::class);
        $this->requestCache   = $this->prophesize(RequestResultCache::class);
        $this->entityBuilder  = $this->prophesize(EntityBuilderInterface::class);
        $this->request        = $this->prophesize(RequestInterface::class);
        $this->genre          = $this->prophesize(Genre::class);

        $this->repository = new GenreRepository(
            $this->requestBuilder->reveal(),
            $this->requestCache->reveal(),
            $this->entityBuilder->reveal()
        );
    }

    public function testGenreRepositoryImplementsGenreRepositoryInterface(): void
    {
        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
    }

    public function testGetMovieGenreReturnExpectedGenre(): void
    {
        $genreId = \random_int(1, 100);
        $data    = [
            'genres' => [
                [
                    'id'   => $genreId,
                    'name' => \uniqid('name', true)
                ]
            ]
        ];
        $content = \json_encode($data);

        $this->requestBuilder->createMovieGenresRequest()->will([$this->request, 'reveal'])->shouldBeCalledOnce();
        $this->requestCache->getRequestBodyContent($this->request->reveal())->willReturn($content);
        $this->entityBuilder->createFromArray(\current($data['genres']))->will([$this->genre, 'reveal']);
        $this->genre->getId()->willReturn($genreId);

        $this->assertSame($this->genre->reveal(), $this->repository->getMovieGenre($genreId));
    }

    public function testGetMovieGenreWithInvalidIdWillReturnNull(): void
    {
        $genreId = \random_int(1, 100);
        $data    = [
            'genres' => [
                [
                    'id'   => $genreId,
                    'name' => \uniqid('name', true)
                ]
            ]
        ];
        $content = \json_encode($data);

        $this->requestBuilder->createMovieGenresRequest()->will([$this->request, 'reveal'])->shouldBeCalledOnce();
        $this->requestCache->getRequestBodyContent($this->request->reveal())->willReturn($content);
        $this->entityBuilder->createFromArray(\current($data['genres']))->will([$this->genre, 'reveal']);
        $this->genre->getId()->willReturn($genreId + 1);

        $result = $this->repository->getMovieGenre($genreId);

        $this->assertNotSame($this->genre->reveal(), $result);
        $this->assertNull($result);
    }

    public function testGetAllMovieGenresWillReturnGeneratorWithAllGenres(): void
    {
        $totalGenres = \random_int(1, 100);
        $data        = [
            'genres' => []
        ];

        for ($i = 0; $i < $totalGenres; $i++) {
            $genreData = [
                'id'   => \random_int(1, 100),
                'name' => \uniqid('name', true),
            ];

            $genre = $this->prophesize(Genre::class);
            $this->entityBuilder->createFromArray($genreData)->willReturn($genre->reveal());

            $data['genres'][] = $genreData;
        }

        $content = \json_encode($data);

        $this->requestBuilder->createMovieGenresRequest()->will([$this->request, 'reveal'])->shouldBeCalledOnce();
        $this->requestCache->getRequestBodyContent($this->request->reveal())->willReturn($content);

        $genrerator = $this->repository->getAllMovieGenres();
        $this->assertInstanceOf(\Generator::class, $genrerator);
        $this->assertCount($totalGenres, $genrerator);
    }
}
