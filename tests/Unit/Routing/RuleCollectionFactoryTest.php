<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Routing;

use Asaliev\Yii2Bridge\Routing\RuleCollection;
use Asaliev\Yii2Bridge\Routing\RuleCollectionFactory;
use PHPUnit\Framework\TestCase;
use yii\base\Application;
use yii\web\UrlManager;
use yii\web\UrlRule;

class RuleCollectionFactoryTest extends TestCase
{
    public function testCreateRuleCollectionWithValidRules(): void
    {
        $yiiRule = $this->createMock(UrlRule::class);
        $yiiRule->pattern = 'test-pattern';

        $urlManager = $this->createMock(UrlManager::class);
        $urlManager->rules = [$yiiRule];

        $app = $this->createMock(Application::class);
        $app->method('getUrlManager')->willReturn($urlManager);

        $factory = new RuleCollectionFactory($app);
        $collection = $factory->create();

        $this->assertCount(1, $collection);
        $this->assertSame($yiiRule, $collection['yii_route_' . md5('test-pattern')]);
    }

    public function testCreateEmptyRuleCollectionWhenNoRulesAreSet(): void
    {
        $urlManager = $this->createMock(UrlManager::class);
        $urlManager->rules = [];

        $app = $this->createMock(Application::class);
        $app->method('getUrlManager')->willReturn($urlManager);

        $factory = new RuleCollectionFactory($app);
        $collection = $factory->create();

        $this->assertCount(0, $collection);
    }
}
