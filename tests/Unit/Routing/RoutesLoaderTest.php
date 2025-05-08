<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Routing;

use Asaliev\Yii2Bridge\Routing\Adapters\RouteAdapterInterface;
use Asaliev\Yii2Bridge\Routing\RoutesLoader;
use Asaliev\Yii2Bridge\Routing\RuleCollectionInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Routing\Route;
use yii\web\UrlRule;

class RoutesLoaderTest extends TestCase
{
    public function testLoadThrowsExceptionWhenLoaderIsAddedTwice(): void
    {
        $ruleCollection = $this->createMock(RuleCollectionInterface::class);
        $routeAdapter = $this->createMock(RouteAdapterInterface::class);
        $loader = new RoutesLoader($ruleCollection, $routeAdapter);

        $loader->load(null, 'yii2_routes');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Do not add the "yii2_routes" loader twice.');

        $loader->load(null, 'yii2_routes');
    }

    public function testLoadRoutesFromRuleCollection(): void
    {
        $rule = $this->createMock(UrlRule::class);
        $route = $this->createMock(Route::class);

        $ruleCollection = $this->createMock(RuleCollectionInterface::class);
        $ruleCollection->expects($this->once())
            ->method('all')
            ->willReturn([$rule]);

        $routeAdapter = $this->createMock(RouteAdapterInterface::class);
        $routeAdapter->expects($this->once())
            ->method('convert')
            ->with($rule)
            ->willReturn($route);

        $loader = new RoutesLoader($ruleCollection, $routeAdapter);
        $routes = $loader->load(null, 'yii2_routes');

        $this->assertCount(1, $routes);
    }

    public function testSupportsYiiRouteTypes(): void
    {
        $ruleCollection = $this->createMock(RuleCollectionInterface::class);
        $routeAdapter = $this->createMock(RouteAdapterInterface::class);
        $loader = new RoutesLoader($ruleCollection, $routeAdapter);

        $this->assertTrue($loader->supports(null, 'yii2_routes'));
        $this->assertFalse($loader->supports(null, 'other_type'));
    }
}
