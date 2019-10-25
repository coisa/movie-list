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

namespace CoiSA\MovieList\Test\Unit\Http\Message;

use CoiSA\MovieList\Http\Message\TmdbRequestBuilder;
use CoiSA\MovieList\Http\Message\TmdbRequestBuilderFactory;
use CoiSA\MovieList\Test\Unit\FactoryTestCase;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * Class TmdbRequestBuilderFactoryTest
 *
 * @package CoiSA\MovieList\Test\Unit\Http\Message
 */
final class TmdbRequestBuilderFactoryTest extends FactoryTestCase
{
    public function getFactoryObject(): callable
    {
        return new TmdbRequestBuilderFactory();
    }

    public function getReturnedObjectClassName(): string
    {
        return TmdbRequestBuilder::class;
    }

    public function getDependencyInjectionClassNames(): array
    {
        return [
            'config' => [
                TmdbRequestBuilderFactory::class => \uniqid('apiKey', true)
            ],
            RequestFactoryInterface::class,
        ];
    }
}
