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

namespace CoiSA\MovieList\Test\Unit\Entity\Builder;

use CoiSA\MovieList\Entity\Builder\EntityBuilderInterface;
use CoiSA\MovieList\Entity\Builder\MovieBuilder;
use CoiSA\MovieList\Entity\Genre;
use CoiSA\MovieList\Entity\Movie;
use CoiSA\MovieList\Repository\GenreRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class MovieBuilderTest
 *
 * @package CoiSA\MovieList\Test\Unit\Entity\Builder
 */
final class MovieBuilderTest extends TestCase
{
    /** @var ObjectProphecy|UriFactoryInterface */
    private $uriFactory;

    /** @var GenreRepositoryInterface|ObjectProphecy */
    private $genreRepository;

    /** @var MovieBuilder */
    private $builder;

    protected function setUp(): void
    {
        $this->uriFactory      = $this->prophesize(UriFactoryInterface::class);
        $this->genreRepository = $this->prophesize(GenreRepositoryInterface::class);

        $this->uriFactory->createUri(
            Argument::type('string')
        )->will([$this->prophesize(UriInterface::class), 'reveal']);

        $this->builder = new MovieBuilder(
            $this->uriFactory->reveal(),
            $this->genreRepository->reveal()
        );
    }

    public function testMovieBuilderImplementsEntityBuilderInterface(): void
    {
        $this->assertInstanceOf(EntityBuilderInterface::class, $this->builder);
    }

    public function testCreateFromArrayWillReturnMovieInstanceWithProvidedData(): void
    {
        $numGenres = \random_int(1, 5);
        $genres    = [];

        for ($i = 0; $i < $numGenres; $i++) {
            $genreData = [
                'id'   => $i,
                'name' => \uniqid('genre', true)
            ];

            $genre = $this->prophesize(Genre::class);
            $genre->getId()->willReturn($genreData['id']);
            $genre->getName()->willReturn($genreData['name']);

            $this->genreRepository->getMovieGenre($genreData['id'])->will([$genre, 'reveal']);

            $genres[] = $genreData;
        }

        $id             = \random_int(1, 100);
        $original_title = \uniqid('original_title', true);
        $overview       = \uniqid('overview', true);
        $release_date   = (new \DateTimeImmutable('+' . $id . ' days'))->format('Y-m-d');
        $genre_ids      = \array_column($genres, 'id');

        $movie = $this->builder->createFromArray(\compact(
            'id',
            'original_title',
            'overview',
            'release_date',
            'genre_ids'
        ));

        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertEquals($id, $movie->getId());
        $this->assertEquals($original_title, $movie->getName());
        $this->assertEquals($overview, $movie->getOverview());
        $this->assertEquals($release_date, $movie->getReleaseDate()->format('Y-m-d'));
        $this->assertNull($movie->getImage());
        $this->assertNull($movie->getBackdrop());

        foreach ($movie->getGenres() as $index => $genre) {
            $this->assertEquals($genres[$index]['id'], $genre->getId());
            $this->assertEquals($genres[$index]['name'], $genre->getName());
        }
    }

    public function testCreateFromArrayWithoutReleaseDateWillReturnMovieInstanceWithNullReleaseDate(): void
    {
        $movie = $this->builder->createFromArray([
            'id'             => \random_int(1, 100),
            'original_title' => \uniqid('original_title', true),
            'overview'       => \uniqid('overview', true)
        ]);

        $this->assertNull($movie->getReleaseDate());
    }

    public function testCreateFromArrayWithInvalidReleaseDateWillReturnMovieInstanceWithNullReleaseDate(): void
    {
        $movie = $this->builder->createFromArray([
            'id'             => \random_int(1, 100),
            'original_title' => \uniqid('original_title', true),
            'overview'       => \uniqid('overview', true),
            'release_date'   => \uniqid('release_date', true),
        ]);

        $this->assertNull($movie->getReleaseDate());
    }

    public function testCreateFromArrayWithPosterPathWillReturnMovieInstanceWithUriGetImage(): void
    {
        $movie = $this->builder->createFromArray([
            'id'             => \random_int(1, 100),
            'original_title' => \uniqid('original_title', true),
            'overview'       => \uniqid('overview', true),
            'poster_path'    => \uniqid('poster_path', true),
        ]);

        $this->assertInstanceOf(UriInterface::class, $movie->getImage());
    }

    public function testCreateFromArrayWithBackdropPathWillReturnMovieInstanceWithUriGetBackdrop(): void
    {
        $movie = $this->builder->createFromArray([
            'id'             => \random_int(1, 100),
            'original_title' => \uniqid('original_title', true),
            'overview'       => \uniqid('overview', true),
            'backdrop_path'  => \uniqid('backdrop_path', true),
        ]);

        $this->assertInstanceOf(UriInterface::class, $movie->getBackdrop());
    }

    public function testCreateFromArrayWithGenresWillReturnMovieInstanceWithSameGenresAsGenreId(): void
    {
        $numGenres = \random_int(1, 5);
        $genres    = [];

        for ($i = 0; $i < $numGenres; $i++) {
            $genreData = [
                'id'   => $i,
                'name' => \uniqid('genre_' . $i . '_', true)
            ];

            $genre = $this->prophesize(Genre::class);
            $genre->getId()->willReturn($genreData['id']);
            $genre->getName()->willReturn($genreData['name']);

            $this->genreRepository->getMovieGenre($genreData['id'])->will([$genre, 'reveal']);

            $genres[] = $genreData;
        }

        $movie = $this->builder->createFromArray([
            'id'             => \random_int(1, 100),
            'original_title' => \uniqid('original_title', true),
            'overview'       => \uniqid('overview', true),
            'backdrop_path'  => \uniqid('backdrop_path', true),
            'genres'         => $genres
        ]);

        foreach ($movie->getGenres() as $index => $genre) {
            $this->assertEquals($genres[$index]['id'], $genre->getId());
            $this->assertEquals($genres[$index]['name'], $genre->getName());
        }
    }

    public function testCreateFromArrayWithNotFoundGEnreWillNotThrowErrors(): void
    {
        $numGenres = \random_int(1, 5);
        $genres    = [];

        for ($i = 0; $i < $numGenres; $i++) {
            $genreData = [
                'id'   => $i,
                'name' => \uniqid('genre', true)
            ];

            $genre = $this->prophesize(Genre::class);
            $genre->getId()->willReturn($genreData['id']);
            $genre->getName()->willReturn($genreData['name']);

            $this->genreRepository->getMovieGenre($genreData['id'])->willReturn(null);

            $genres[] = $genreData;
        }

        $movie = $this->builder->createFromArray([
            'id'             => \random_int(1, 100),
            'original_title' => \uniqid('original_title', true),
            'overview'       => \uniqid('overview', true),
            'backdrop_path'  => \uniqid('backdrop_path', true),
            'genres'         => $genres
        ]);

        $this->assertEmpty($movie->getGenres());
    }
}
