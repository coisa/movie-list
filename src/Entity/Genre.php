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

namespace CoiSA\MovieList\Entity;

/**
 * Class Genre
 *
 * @package CoiSA\MovieList\Entity
 */
class Genre
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /**
     * Genre constructor.
     *
     * @param int    $id
     * @param string $name
     */
    public function __construct(
        int $id,
        string $name
    ) {
        $this->id   = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
