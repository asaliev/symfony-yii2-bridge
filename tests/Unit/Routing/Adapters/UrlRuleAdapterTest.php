<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Routing\Adapters;

use Asaliev\Yii2Bridge\Controller\DispatcherController;
use Asaliev\Yii2Bridge\Routing\Adapters\UrlRuleAdapter;
use PHPUnit\Framework\TestCase;
use yii\web\UrlManager;
use yii\web\UrlRule;

class UrlRuleAdapterTest extends TestCase
{
    public function testConvertYiiRuleWithPatternAndPlaceholders(): void
    {
        $yiiRule = $this->createMock(UrlRule::class);
        $yiiRule->pattern = '#^foo/(?P<id>\d+)$#u';
        $yiiRule->verb = ['GET'];
        $yiiRule->defaults = ['controller' => 'foo/view'];
        $yiiRule->host = 'example.com';

        $adapter = new UrlRuleAdapter();
        $route = $adapter->convert($yiiRule);

        $this->assertEquals('/foo/{id}', $route->getPath());
        $this->assertEquals(['id' => '\d+'], $route->getRequirements());
        $this->assertEquals(['GET'], $route->getMethods());
        $this->assertEquals('example.com', $route->getHost());
        $this->assertArrayHasKey('_controller', $route->getDefaults());
        $this->assertArrayHasKey('yii2_rule_name', $route->getDefaults());
        $this->assertArrayHasKey('yii2_rule_definition', $route->getDefaults());
    }

    public function testConvertYiiRuleWithoutPatternOrPlaceholders(): void
    {
        $yiiRule = $this->createMock(UrlRule::class);
        $yiiRule->pattern = '#^bar$#u';
        $yiiRule->verb = null;
        $yiiRule->defaults = ['controller' => 'site/bar'];
        $yiiRule->host = '';

        $adapter = new UrlRuleAdapter();
        $route = $adapter->convert($yiiRule);

        $this->assertEquals('/bar', $route->getPath());
        $this->assertEmpty($route->getRequirements());
        $this->assertEmpty($route->getMethods());
        $this->assertEquals('', $route->getHost());
        $this->assertArrayHasKey('_controller', $route->getDefaults());
        $this->assertArrayHasKey('yii2_rule_name', $route->getDefaults());
        $this->assertArrayHasKey('yii2_rule_definition', $route->getDefaults());
    }

    public function testConvertWithEmptyDefaults(): void
    {
        $yiiRule = $this->createMock(UrlRule::class);
        $yiiRule->pattern = '#^qux$#u';
        $yiiRule->verb = ['POST'];
        $yiiRule->defaults = [];
        $yiiRule->host = '';

        $adapter = new UrlRuleAdapter();
        $route = $adapter->convert($yiiRule);

        $this->assertEquals('/qux', $route->getPath());
        $this->assertEmpty($route->getRequirements());
        $this->assertEquals(['POST'], $route->getMethods());
        $this->assertEquals('', $route->getHost());
        $this->assertArrayHasKey('_controller', $route->getDefaults());
        $this->assertArrayHasKey('yii2_rule_name', $route->getDefaults());
        $this->assertArrayHasKey('yii2_rule_definition', $route->getDefaults());
    }
}
