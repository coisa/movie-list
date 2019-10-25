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

namespace CoiSA\MovieList\Http\Message;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * Class TmdbRequestBuilderFactory
 *
 * @package CoiSA\MovieList\Http\Message
 */
final class TmdbRequestBuilderFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return TmdbRequestBuilder
     */
    public function __invoke(ContainerInterface $container): TmdbRequestBuilder
    {
        $config         = $container->get('config');
        $apiKey         = $config[self::class];
        $requestFactory = $container->get(RequestFactoryInterface::class);

        return new TmdbRequestBuilder($apiKey, $requestFactory);
    }
}
