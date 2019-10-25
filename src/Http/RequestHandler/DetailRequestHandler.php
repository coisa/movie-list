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
 * Class DetailRequestHandler
 *
 * @package CoiSA\MovieList\Http\RequestHandler
 */
class DetailRequestHandler implements RequestHandlerInterface
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
        $movieId = (int) $request->getAttribute('id');
        $movie   = $this->movieRepository->getDetail($movieId);
        $content = $this->templateRenderer->render('pages::detail', \compact('movie'));

        return new HtmlResponse($content);
    }
}
