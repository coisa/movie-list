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

namespace CoiSA\MovieList\Test\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class FactoryTestCase
 *
 * @package CoiSA\MovieList\Test\Unit
 */
abstract class FactoryTestCase extends TestCase
{
    /**
     * @return array
     */
    abstract public function getDependencyInjectionClassNames(): array;

    /**
     * @return callable
     */
    abstract public function getFactoryObject(): callable;

    /**
     * @return string
     */
    abstract public function getReturnedObjectClassName(): string;

    /**
     * @return array
     */
    public function provideDependencyClassNames(): array
    {
        $dependecyInjection = $this->getDependencyInjectionClassNames();

        $provided = [];
        foreach ($dependecyInjection as $key => $value) {
            $provided[] = [\is_numeric($key) ? $value : $key];
        }

        return $provided;
    }

    public function testInvokeWillReturnProvidedObjectClassNameInstance(): void
    {
        $factory   = $this->getFactoryObject();
        $container = $this->getContainerProphecy();

        $return = ($factory)($container->reveal());
        $this->assertInstanceOf($this->getReturnedObjectClassName(), $return);
    }

    /**
     * @dataProvider provideDependencyClassNames
     *
     * @param mixed $dependecy
     */
    public function testInvokeWithoutDependecyWillThrowNotFoundException($dependecy): void
    {
        $exception = new class () extends \Exception implements NotFoundExceptionInterface {
        };
        $factory   = $this->getFactoryObject();
        $container = $this->getContainerProphecy();

        $container->get($dependecy)->willThrow($exception);

        $this->expectException(NotFoundExceptionInterface::class);
        ($factory)($container->reveal());
    }

    /**
     * @dataProvider provideDependencyClassNames
     *
     * @param mixed $dependecy
     */
    public function testInvokeWithInvalidDependencyObjectWillThrowErrorException($dependecy): void
    {
        if (\is_array($dependecy)) {
            $dependecy = \key($dependecy);
        }

        $factory   = $this->getFactoryObject();
        $container = $this->getContainerProphecy();

        $container->get($dependecy)->willReturn(new \stdClass());

        $this->expectException(\Error::class);
        ($factory)($container->reveal());
    }

    /**
     * @return ObjectProphecy
     */
    private function getContainerProphecy(): ObjectProphecy
    {
        $dependecyInjection = $this->getDependencyInjectionClassNames();
        $container          = $this->prophesize(ContainerInterface::class);

        foreach ($dependecyInjection as $key => $value) {
            $dependecyClassName = \is_numeric($key) ? $value : $key;

            if (\is_string($value)) {
                $dependecy = $this->prophesize($dependecyClassName);
                $container->get($dependecyClassName)->will([$dependecy, 'reveal']);
            } else {
                $container->get($dependecyClassName)->willReturn($value);
            }
        }

        return $container;
    }
}
