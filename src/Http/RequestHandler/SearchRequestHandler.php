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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class SearchRequestHandler
 *
 * @package CoiSA\MovieList\Http\RequestHandler
 */
class SearchRequestHandler implements RequestHandlerInterface
{
    /** @var TemplateRendererInterface */
    private $templateRenderer;

    /** @var MovieRepositoryInterface */
    private $movieRepository;

    /**
     * IndexRequestHandler constructor.
     *
     * @param MovieRepositoryInterface  $movieRepository
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(
        MovieRepositoryInterface $movieRepository,
        TemplateRendererInterface $templateRenderer
    ) {
        $this->movieRepository  = $movieRepository;
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params  = $request->getQueryParams();
        $query   = $params['query'];
        $movies  = $this->movieRepository->search($query);
        $content = $this->templateRenderer->render('pages::search', \compact('movies', 'query'));

        return new HtmlResponse($content);
    }
}
