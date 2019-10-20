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

namespace CoiSA\MovieList\Test\Unit\Http\Cache;

use Cache\Adapter\Common\Exception\InvalidArgumentException;
use CoiSA\MovieList\Http\Cache\RequestResultCache;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class RequestResultCacheFactoryTest
 *
 * @package CoiSA\MovieList\Test\Unit\Http\Cache
 */
final class RequestResultCacheTest extends TestCase
{
    /** @var ClientInterface|ObjectProphecy */
    private $client;

    /** @var CacheInterface|ObjectProphecy */
    private $cache;

    /** @var LoggerInterface|ObjectProphecy */
    private $logger;

    /** @var ObjectProphecy|RequestInterface */
    private $request;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    /** @var ObjectProphecy|StreamInterface */
    private $body;

    /** @var string */
    private $content;

    /** @var RequestResultCache */
    private $requestResultCache;

    protected function setUp(): void
    {
        $this->client   = $this->prophesize(ClientInterface::class);
        $this->cache    = $this->prophesize(CacheInterface::class);
        $this->logger   = $this->prophesize(LoggerInterface::class);
        $this->request  = $this->prophesize(RequestInterface::class);
        $this->response = $this->prophesize(ResponseInterface::class);
        $this->body     = $this->prophesize(StreamInterface::class);
        $this->content  = \uniqid('content', true);

        $this->client->sendRequest($this->request->reveal())->will([$this->response, 'reveal']);
        $this->response->getBody()->will([$this->body, 'reveal']);
        $this->body->getContents()->willReturn($this->content);

        $this->requestResultCache = new RequestResultCache(
            $this->client->reveal(),
            $this->cache->reveal(),
            $this->logger->reveal()
        );
    }

    public function testGetRequestBodyContentWillNoticeInvalidCacheKey(): void
    {
        $this->cache->has(Argument::type('string'))->willThrow(new InvalidArgumentException());
        $this->cache->set(
            Argument::type('string'),
            $this->content,
            Argument::any()
        )->willThrow(new InvalidArgumentException());
        $this->logger->notice(Argument::type('string'), Argument::type('array'))->shouldBeCalledTimes(2);
        $this->logger->info(Argument::type('string'), Argument::type('array'))->shouldBeCalledTimes(3);

        $this->request->getUri()->willReturn(\uniqid('uri', true));
        $this->request->getMethod()->willReturn('GET');
        $this->response->getStatusCode()->willReturn(200);

        $this->requestResultCache->getRequestBodyContent($this->request->reveal());
    }

    public function testGetRequestBodyContentReturnFromCacheIfExists(): void
    {
        $this->cache->has(Argument::type('string'))->willReturn(true);
        $this->logger->info(Argument::type('string'), Argument::type('array'))->shouldBeCalledOnce();
        $this->cache->get(Argument::type('string'))->willReturn($this->content)->shouldBeCalledOnce();

        $content = $this->requestResultCache->getRequestBodyContent($this->request->reveal());

        $this->assertSame($this->content, $content);
    }

    public function testGetRequestBodyShouldReturnEmptyStringWhenClientExceptionWasThrown(): void
    {
        $exception = new class () extends \Exception implements ClientExceptionInterface {
        };
        $this->cache->has(Argument::type('string'))->willReturn(false);
        $this->client->sendRequest($this->request->reveal())->willThrow($exception);

        $content = $this->requestResultCache->getRequestBodyContent($this->request->reveal());

        $this->assertEmpty($content);
    }

    public function testGetRequestBodyShouldNotSetCacheForNonCacheableRequests(): void
    {
        $this->cache->has(Argument::type('string'))->willReturn(false);
        $this->request->getUri()->willReturn(\uniqid('uri', true));
        $this->request->getMethod()->willReturn('POST');

        $this->cache->set(
            Argument::type('string'),
            Argument::type('string'),
            Argument::any()
        )->shouldNotBeCalled();

        $this->requestResultCache->getRequestBodyContent($this->request->reveal());
    }

    public function testGetRequestBodyShouldNotSetCacheForNonCacheableResponses(): void
    {
        $this->cache->has(Argument::type('string'))->willReturn(false);
        $this->request->getUri()->willReturn(\uniqid('uri', true));
        $this->request->getMethod()->willReturn('GET');
        $this->response->getStatusCode()->willReturn(500);

        $this->cache->set(
            Argument::type('string'),
            Argument::type('string'),
            Argument::any()
        )->shouldNotBeCalled();

        $this->requestResultCache->getRequestBodyContent($this->request->reveal());
    }

    public function testGetRequestBodyWithCacheableResponseWillSetCache(): void
    {
        $this->cache->has(Argument::type('string'))->willReturn(false);
        $this->request->getUri()->willReturn(\uniqid('uri', true));
        $this->request->getMethod()->willReturn('GET');
        $this->response->getStatusCode()->willReturn(200);

        $this->cache->set(
            Argument::type('string'),
            $this->content,
            Argument::any()
        )->shouldBeCalled();

        $this->requestResultCache->getRequestBodyContent($this->request->reveal());
    }
}
