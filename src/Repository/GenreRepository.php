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

namespace CoiSA\MovieList\Repository;

use CoiSA\MovieList\Entity\Builder\EntityBuilderInterface;
use CoiSA\MovieList\Entity\Genre;
use CoiSA\MovieList\Http\Cache\RequestResultCache;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderInterface;

/**
 * Class GenreRepository
 *
 * @package CoiSA\MovieList\Repository
 */
class GenreRepository implements GenreRepositoryInterface
{
    /** @var TmdbRequestBuilderInterface */
    private $requestBuilder;

    /** @var RequestResultCache */
    private $requestResultCache;

    /** @var EntityBuilderInterface */
    private $genreBuilder;

    /**
     * GenreRepository constructor.
     *
     * @param TmdbRequestBuilderInterface $requestBuilder
     * @param RequestResultCache          $requestResultCache
     * @param EntityBuilderInterface      $genreBuilder
     */
    public function __construct(
        TmdbRequestBuilderInterface $requestBuilder,
        RequestResultCache $requestResultCache,
        EntityBuilderInterface $genreBuilder
    ) {
        $this->requestBuilder     = $requestBuilder;
        $this->requestResultCache = $requestResultCache;
        $this->genreBuilder       = $genreBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getMovieGenre(int $genreId): ?Genre
    {
        foreach ($this->getAllMovieGenres() as $genre) {
            if ($genreId !== $genre->getId()) {
                continue;
            }

            return $genre;
        }

        return null;
    }

    /**
     * Return all movie genres
     *
     * @return iterable
     */
    public function getAllMovieGenres(): iterable
    {
        $parsedBody = $this->getParsedBody();
        $results    = $parsedBody['genres'];

        foreach ($results as $result) {
            yield $this->genreBuilder->createFromArray($result);
        }
    }

    /**
     * @return array
     */
    private function getParsedBody(): array
    {
        $request  = $this->requestBuilder->createMovieGenresRequest();
        $content  = $this->requestResultCache->getRequestBodyContent($request);

        return \json_decode($content, true);
    }
}
