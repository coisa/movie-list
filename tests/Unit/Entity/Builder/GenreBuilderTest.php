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
use CoiSA\MovieList\Entity\Builder\GenreBuilder;
use CoiSA\MovieList\Entity\Genre;
use PHPUnit\Framework\TestCase;

/**
 * Class MovieBuilderFactoryTest
 *
 * @package CoiSA\MovieList\Test\Unit\Entity\Builder
 */
final class GenreBuilderTest extends TestCase
{
    /** @var GenreBuilder */
    private $builder;

    protected function setUp(): void
    {
        $this->builder = new GenreBuilder();
    }

    public function testGenreBuilderImplementsEntityBuilderInterface(): void
    {
        $this->assertInstanceOf(EntityBuilderInterface::class, $this->builder);
    }

    public function testCreateFromArrayWithWillReturnGenreInstanceWithProvidedData(): void
    {
        $id   = \random_int(1, 100);
        $name = \uniqid('name', true);

        $genre = $this->builder->createFromArray(\compact('id', 'name'));

        $this->assertInstanceOf(Genre::class, $genre);
        $this->assertEquals($id, $genre->getId());
        $this->assertEquals($name, $genre->getName());
    }

    public function testCreateFromArrayWithInvalidIdWillThrowError(): void
    {
        $id   = \uniqid('id', true);
        $name = \uniqid('name', true);

        $this->expectException(\Error::class);
        $this->builder->createFromArray(\compact('id', 'name'));
    }

    public function testCreateFromArrayWithInvalidNameWillThrowError(): void
    {
        $id   = \random_int(1, 100);
        $name = \random_int(1, 100);

        $this->expectException(\Error::class);
        $this->builder->createFromArray(\compact('id', 'name'));
    }

    public function testCreateFromArrayWithoutIdWillThrowError(): void
    {
        $name = \uniqid('name', true);

        $this->expectException(\Error::class);
        $this->builder->createFromArray(\compact('name'));
    }

    public function testCreateFromArrayWithoutNameWillThrowError(): void
    {
        $id = \random_int(1, 100);

        $this->expectException(\Error::class);
        $this->builder->createFromArray(\compact('id'));
    }
}
