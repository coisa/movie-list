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

namespace CoiSA\MovieList\Test\Unit\Entity;

use CoiSA\MovieList\Entity\Genre;
use CoiSA\MovieList\Entity\Movie;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

/**
 * Class MovieTest
 *
 * @package CoiSA\MovieList\Test\Unit\Entity
 */
final class MovieTest extends TestCase
{
    /** @var array */
    private $givenData;

    /** @var Movie */
    private $movie;

    protected function setUp(): void
    {
        $id          = \random_int(1, 100);
        $name        = \uniqid('name', true);
        $overview    = \uniqid('overview', true);
        $releaseDate = new \DateTimeImmutable('+' . $id . ' days');
        $image       = $this->prophesize(UriInterface::class)->reveal();
        $backdrop    = $this->prophesize(UriInterface::class)->reveal();

        $numGenres = \random_int(1, 5);
        $genres    = [];

        for ($i = 0; $i < $numGenres; $i++) {
            $genres[] = $this->prophesize(Genre::class)->reveal();
        }

        $this->givenData = \compact(
            'id',
            'name',
            'overview',
            'releaseDate',
            'image',
            'backdrop'
        );

        $this->movie               = new Movie(...\array_merge(\array_values($this->givenData), $genres));
        $this->givenData['genres'] = $genres;
    }

    public function testGettersWillReturnSameConstructorGivenData(): void
    {
        $this->assertSame($this->givenData['id'], $this->movie->getId());
        $this->assertSame($this->givenData['name'], $this->movie->getName());
        $this->assertSame($this->givenData['overview'], $this->movie->getOverview());
        $this->assertSame($this->givenData['releaseDate'], $this->movie->getReleaseDate());
        $this->assertSame($this->givenData['image'], $this->movie->getImage());
        $this->assertSame($this->givenData['backdrop'], $this->movie->getBackdrop());
        $this->assertSame($this->givenData['genres'], $this->movie->getGenres());
    }
}
