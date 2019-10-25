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
use PHPUnit\Framework\TestCase;

final class GenreTest extends TestCase
{
    public function testGetIdReturnSameConstructorGivenId(): void
    {
        $id   = \random_int(1, 100);
        $name = \uniqid('name', true);

        $genre = new Genre($id, $name);

        $this->assertSame($id, $genre->getId());
    }

    public function testGetNameReturnSameConstructorGivenName(): void
    {
        $id   = \random_int(1, 100);
        $name = \uniqid('name', true);

        $genre = new Genre($id, $name);

        $this->assertSame($name, $genre->getName());
    }
}
