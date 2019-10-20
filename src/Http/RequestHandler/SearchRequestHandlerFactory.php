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

namespace CoiSA\MovieList\Http\RequestHandler;

use CoiSA\MovieList\Repository\MovieRepositoryInterface;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class SearchRequestHandlerFactory
 *
 * @package CoiSA\MovieList\Http\RequestHandler
 */
final class SearchRequestHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return SearchRequestHandler
     */
    public function __invoke(ContainerInterface $container): SearchRequestHandler
    {
        $templateRenderer = $container->get(TemplateRendererInterface::class);
        $movieRepository  = $container->get(MovieRepositoryInterface::class);

        return new SearchRequestHandler($movieRepository, $templateRenderer);
    }
}
