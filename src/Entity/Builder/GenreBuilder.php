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

namespace CoiSA\MovieList\Entity\Builder;

use CoiSA\MovieList\Entity\Genre;

/**
 * Class GenreBuilder
 *
 * @package CoiSA\MovieList\Entity\Builder
 */
class GenreBuilder implements EntityBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function createFromArray(array $result): Genre
    {
        return new Genre(
            $result['id'] ?? null,
            $result['name'] ?? null
        );
    }
}
