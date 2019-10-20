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

use CoiSA\MovieList\ConfigProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigProviderTest
 *
 * @package CoiSA\MovieList\Test\Unit
 */
final class ConfigProviderTest extends TestCase
{
    /** @var ConfigProvider */
    private $configProvider;

    protected function setUp(): void
    {
        $this->configProvider = new ConfigProvider();
    }

    public function testInvokeWillReturnArrayWithDependenciesIndex(): void
    {
        $config = ($this->configProvider)();

        $this->assertIsArray($config);
        $this->assertArrayHasKey('dependencies', $config);
    }

    public function testInvokeWillReturnSameDependenciesAsGetDependencies(): void
    {
        $config = ($this->configProvider)();

        $this->assertSame($this->configProvider->getDependencies(), $config['dependencies']);
    }

    public function testGetDependenciesWillReturnArrayWithAliases(): void
    {
        $dependencies = $this->configProvider->getDependencies();

        $this->assertIsArray($dependencies);
        $this->assertArrayHasKey('aliases', $dependencies);
    }

    public function testGetDependenciesWillReturnArrayWithInvokables(): void
    {
        $dependencies = $this->configProvider->getDependencies();

        $this->assertIsArray($dependencies);
        $this->assertArrayHasKey('invokables', $dependencies);
    }

    public function testGetDependenciesWillReturnArrayWithFactories(): void
    {
        $dependencies = $this->configProvider->getDependencies();

        $this->assertIsArray($dependencies);
        $this->assertArrayHasKey('factories', $dependencies);
    }

    public function testGetAliasesWillReturnSameAliasesAsGetDependencies(): void
    {
        $dependencies = $this->configProvider->getDependencies();

        $this->assertSame($dependencies['aliases'], $this->configProvider->getAliases());
    }

    public function testGetInvokablesWillReturnSameInvokablesAsGetDependencies(): void
    {
        $dependencies = $this->configProvider->getDependencies();

        $this->assertSame($dependencies['invokables'], $this->configProvider->getInvokables());
    }

    public function testGetFactoriesWillReturnSameFactoriesAsGetDependencies(): void
    {
        $dependencies = $this->configProvider->getDependencies();

        $this->assertSame($dependencies['factories'], $this->configProvider->getFactories());
    }
}
