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

use CoiSA\MovieList\Entity\Movie;
use CoiSA\MovieList\Http\RequestHandler\DetailRequestHandler;
use CoiSA\MovieList\Repository\MovieRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class DetailRequestHandlerTest
 *
 * @package CoiSA\MovieList\Test\Unit\Http\RequestHandler
 */
final class DetailRequestHandlerTest extends TestCase
{
    /** @var MovieRepositoryInterface|ObjectProphecy */
    private $repository;

    /** @var ObjectProphecy|TemplateRendererInterface */
    private $templateRenderer;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var Movie|ObjectProphecy */
    private $movie;

    /** @var DetailRequestHandler */
    private $requestHandler;

    protected function setUp(): void
    {
        $this->repository       = $this->prophesize(MovieRepositoryInterface::class);
        $this->templateRenderer = $this->prophesize(TemplateRendererInterface::class);
        $this->serverRequest    = $this->prophesize(ServerRequestInterface::class);
        $this->movie            = $this->prophesize(Movie::class);

        $this->requestHandler = new DetailRequestHandler(
            $this->repository->reveal(),
            $this->templateRenderer->reveal()
        );
    }

    public function testDetailRequestHandlerImplementsRequestHandlerInterface(): void
    {
        $this->assertInstanceOf(RequestHandlerInterface::class, $this->requestHandler);
    }

    public function testHandleWillReturnHtmlResponseWithDetailMovieTemplate(): void
    {
        $movieId = \random_int(1, 100);
        $content = \uniqid('content', true);

        $this->serverRequest->getAttribute('id')->willReturn($movieId);
        $this->repository->getDetail($movieId)->will([$this->movie, 'reveal'])->shouldBeCalledOnce();
        $this->templateRenderer->render(
            'pages::detail',
            ['movie' => $this->movie->reveal()]
        )->willReturn($content)->shouldBeCalledOnce();

        $response = $this->requestHandler->handle($this->serverRequest->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertSame($content, $response->getBody()->getContents());
    }
}
