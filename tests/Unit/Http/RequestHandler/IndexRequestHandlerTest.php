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

use CoiSA\MovieList\Http\RequestHandler\IndexRequestHandler;
use CoiSA\MovieList\Repository\MovieRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class IndexRequestHandlerTest
 *
 * @package CoiSA\MovieList\Test\Unit\Http\RequestHandler
 */
final class IndexRequestHandlerTest extends TestCase
{
    /** @var MovieRepositoryInterface|ObjectProphecy */
    private $repository;

    /** @var ObjectProphecy|TemplateRendererInterface */
    private $templateRenderer;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var IndexRequestHandler */
    private $requestHandler;

    protected function setUp(): void
    {
        $this->repository       = $this->prophesize(MovieRepositoryInterface::class);
        $this->templateRenderer = $this->prophesize(TemplateRendererInterface::class);
        $this->serverRequest    = $this->prophesize(ServerRequestInterface::class);

        $this->requestHandler = new IndexRequestHandler(
            $this->repository->reveal(),
            $this->templateRenderer->reveal()
        );
    }

    public function testIndexRequestHandlerImplementsRequestHandlerInterface(): void
    {
        $this->assertInstanceOf(RequestHandlerInterface::class, $this->requestHandler);
    }

    public function testHandleWillReturnHtmlResponseWithUpcomingMoviesTemplate(): void
    {
        $movies  = [\uniqid('movies', true)];
        $content = \uniqid('content', true);

        $this->repository->getUpcoming()->willReturn($movies)->shouldBeCalledOnce();
        $this->templateRenderer->render(
            'pages::index',
            \compact('movies')
        )->willReturn($content)->shouldBeCalledOnce();

        $response = $this->requestHandler->handle($this->serverRequest->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertSame($content, $response->getBody()->getContents());
    }
}
