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

use Psr\Http\Message\UriInterface;

/**
 * Class Movie
 *
 * @package CoiSA\MovieList\Entity
 */
class Movie
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $overview;

    /** @var null|\DateTimeInterface */
    private $releaseDate;

    /** @var null|UriInterface */
    private $image;

    /** @var null|UriInterface */
    private $backdrop;

    /** @var Genre[] */
    private $genres;

    /**
     * Movie constructor.
     *
     * @param int                     $id
     * @param string                  $name
     * @param string                  $overview
     * @param null|\DateTimeInterface $releaseDate
     * @param UriInterface            $image
     * @param UriInterface            $backdrop
     * @param Genre                   ...$genres
     */
    public function __construct(
        int $id,
        string $name,
        string $overview,
        ?\DateTimeInterface $releaseDate,
        ?UriInterface $image,
        ?UriInterface $backdrop,
        Genre ...$genres
    ) {
        $this->id          = $id;
        $this->name        = $name;
        $this->overview    = $overview;
        $this->releaseDate = $releaseDate;
        $this->image       = $image;
        $this->backdrop    = $backdrop;
        $this->genres      = $genres;
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

    /**
     * @return string
     */
    public function getOverview(): string
    {
        return $this->overview;
    }

    /**
     * @return Genre[]
     */
    public function getGenres(): iterable
    {
        return $this->genres;
    }

    /**
     * @return null|\DateTimeInterface
     */
    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    /**
     * @return null|UriInterface
     */
    public function getImage(): ?UriInterface
    {
        return $this->image;
    }

    /**
     * @return null|UriInterface
     */
    public function getBackdrop(): ?UriInterface
    {
        return $this->backdrop;
    }
}
