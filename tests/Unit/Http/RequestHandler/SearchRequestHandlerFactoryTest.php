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

namespace CoiSA\MovieList\Test\Unit\Http\RequestHandler;

use CoiSA\MovieList\Http\RequestHandler\SearchRequestHandler;
use CoiSA\MovieList\Http\RequestHandler\SearchRequestHandlerFactory;
use CoiSA\MovieList\Repository\MovieRepositoryInterface;
use CoiSA\MovieList\Test\Unit\FactoryTestCase;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class SearchRequestHandlerFactoryTest
 *
 * @package CoiSA\MovieList\Test\Unit\Http\RequestHandler
 */
final class SearchRequestHandlerFactoryTest extends FactoryTestCase
{
    public function getFactoryObject(): callable
    {
        return new SearchRequestHandlerFactory();
    }

    public function getReturnedObjectClassName(): string
    {
        return SearchRequestHandler::class;
    }

    public function getDependencyInjectionClassNames(): array
    {
        return [
            TemplateRendererInterface::class,
            MovieRepositoryInterface::class,
        ];
    }
}
