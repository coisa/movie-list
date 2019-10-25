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

use CoiSA\MovieList\Http\RequestHandler\DetailRequestHandler;
use CoiSA\MovieList\Http\RequestHandler\IndexRequestHandler;
use CoiSA\MovieList\Http\RequestHandler\SearchRequestHandler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/', IndexRequestHandler::class, 'index');
    $app->get('/detail/{id:\d+}', DetailRequestHandler::class, 'detail');
    $app->get('/search', SearchRequestHandler::class, 'search');
};
