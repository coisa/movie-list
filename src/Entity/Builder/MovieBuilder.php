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

use CoiSA\MovieList\Entity\Movie;
use CoiSA\MovieList\Repository\GenreRepositoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Movie
 *
 * @package CoiSA\MovieList\Entity\Builder
 */
class MovieBuilder implements EntityBuilderInterface
{
    /** @const string */
    private const ORIGINAL_IMAGE_URL = 'https://image.tmdb.org/t/p/original';

    /** @var UriFactoryInterface */
    private $uriFactory;

    /** @var GenreRepositoryInterface */
    private $genreRepository;

    /**
     * MovieBuilder constructor.
     *
     * @param UriFactoryInterface $uriFactory
     */
    public function __construct(
        UriFactoryInterface $uriFactory,
        GenreRepositoryInterface $genreRepository
    ) {
        $this->uriFactory      = $uriFactory;
        $this->genreRepository = $genreRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function createFromArray(array $result): Movie
    {
        return new Movie(
            $result['id'],
            $result['original_title'],
            $result['overview'],
            $this->createReleaseDate($result),
            $this->createPosterImage($result),
            $this->createBackdropImage($result),
            ...$this->createGenres($result)
        );
    }

    /**
     * @param array $result
     *
     * @return null|\DateTimeImmutable
     */
    private function createReleaseDate(array $result): ?\DateTimeImmutable
    {
        if (empty($result['release_date'])) {
            return null;
        }

        $releaseDate = \DateTimeImmutable::createFromFormat('Y-m-d', $result['release_date']);

        if (false === $releaseDate) {
            return null;
        }

        return $releaseDate;
    }

    /**
     * @param array $result
     *
     * @return array
     */
    private function createGenres(array $result): array
    {
        if (\array_key_exists('genres', $result)) {
            $result['genre_ids'] = \array_column($result['genres'], 'id');
        }

        $genres = [];

        if (empty($result['genre_ids'])) {
            return $genres;
        }

        foreach ($result['genre_ids'] as $genreId) {
            $genre = $this->genreRepository->getMovieGenre($genreId);

            if (!$genre) {
                continue;
            }

            $genres[] = $genre;
        }

        return $genres;
    }

    /**
     * @param array $result
     *
     * @return null|UriInterface
     */
    private function createPosterImage(array $result): ?UriInterface
    {
        if (empty($result['poster_path'])) {
            return null;
        }

        return $this->uriFactory->createUri(self::ORIGINAL_IMAGE_URL . $result['poster_path']);
    }

    /**
     * @param array $result
     *
     * @return null|UriInterface
     */
    private function createBackdropImage(array $result): ?UriInterface
    {
        if (empty($result['backdrop_path'])) {
            return null;
        }

        return $this->uriFactory->createUri(self::ORIGINAL_IMAGE_URL . $result['backdrop_path']);
    }
}
