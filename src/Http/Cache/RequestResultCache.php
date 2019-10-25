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

namespace CoiSA\MovieList\Http\Cache;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class RequestResultCache
 *
 * @package CoiSA\MovieList\Http\Cache
 */
class RequestResultCache
{
    /** @const string */
    private const DEFAULT_CACHE_TTL = 'P1D';

    /** @var ClientInterface */
    private $client;

    /** @var CacheInterface */
    private $cache;

    /** @var LoggerInterface */
    private $logger;

    /**
     * RequestResultCache constructor.
     *
     * @param ClientInterface $client
     * @param CacheInterface  $cache
     * @param LoggerInterface $logger
     */
    public function __construct(
        ClientInterface $client,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $this->client = $client;
        $this->cache  = $cache;
        $this->logger = $logger;
    }

    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    public function getRequestBodyContent(RequestInterface $request): string
    {
        $method   = $request->getMethod();
        $uri      = (string) $request->getUri();
        $cacheKey = $this->getCacheKey($request);

        try {
            if ($this->cache->has($cacheKey)) {
                $this->logger->info(
                    'Request [{method}] "{uri}" was returned from cache {cacheKey}',
                    \compact('cacheKey', 'method', 'uri')
                );

                return $this->cache->get($cacheKey);
            }
        } catch (InvalidArgumentException $exception) {
            $this->logger->notice(
                'It was not able to get cache key "{cacheKey}" because is not a legal value.',
                \compact('cacheKey')
            );
        }

        try {
            $this->logger->info(
                'Sending a request to [{method}] "{uri}".',
                \compact('method', 'uri')
            );

            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $exception) {
            $this->logger->error(
                'An exception was thrown when trying to send request [{method}] "{uri}".',
                \compact('exception', 'method', 'uri')
            );

            return '';
        }

        $body    = $response->getBody();
        $content = $body->getContents();

        if ($this->isCacheable($request, $response)) {
            try {
                $this->logger->info(
                    'Setting cache key {cacheKey}.',
                    \compact('cacheKey', 'content')
                );

                $this->cache->set($cacheKey, $content, new \DateInterval(self::DEFAULT_CACHE_TTL));
            } catch (InvalidArgumentException $exception) {
                $this->logger->notice(
                    'It was not able to set cache key {cacheKey} because is not a legal value.',
                    \compact('cacheKey')
                );
            }
        }

        $this->logger->info('Response content was returned.', \compact('method', 'uri', 'content'));

        return $content;
    }

    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    private function getCacheKey(RequestInterface $request): string
    {
        $uri = (string) $request->getUri();

        return \sprintf(
            '%s%s',
            $request->getMethod(),
            \sha1($uri)
        );
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private function isCacheable(RequestInterface $request, ResponseInterface $response): bool
    {
        return \in_array($request->getMethod(), ['GET', 'HEAD'], true) && $response->getStatusCode() === 200;
    }
}
