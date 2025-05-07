<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Routing;

use Asaliev\Yii2Bridge\Routing\RuleCollection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use yii\web\UrlRuleInterface;

class RuleCollectionTest extends TestCase
{
    public function testOffsetSetThrowsExceptionWhenAddingInvalidRule(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must implement UrlRuleInterface.');

        $collection = new RuleCollection();
        $collection->offsetSet(null, new stdClass()); // @phpstan-ignore-line
    }

    public function testOffsetSetRule(): void
    {
        $rule = $this->createMock(UrlRuleInterface::class);
        $collection = new RuleCollection();

        $collection->offsetSet(null, $rule);

        $this->assertCount(1, $collection);
        $this->assertSame($rule, $collection->offsetGet(0));
    }

    public function testOffsetGetByOffset(): void
    {
        $rule = $this->createMock(UrlRuleInterface::class);
        $collection = new RuleCollection([$rule]);

        $this->assertTrue($collection->offsetExists(0));
        $this->assertSame($rule, $collection->offsetGet(0));
    }

    public function testOffsetGetReturnsNullForNonExistentOffset(): void
    {
        $collection = new RuleCollection();

        $this->assertNull($collection->offsetGet(99));
    }

    public function testOffsetUnsetByOffset(): void
    {
        $rule = $this->createMock(UrlRuleInterface::class);
        $collection = new RuleCollection([$rule]);

        $collection->offsetUnset(0);

        $this->assertCount(0, $collection);
        $this->assertFalse($collection->offsetExists(0));
    }

    public function testCount(): void
    {
        $rule1 = $this->createMock(UrlRuleInterface::class);
        $rule2 = $this->createMock(UrlRuleInterface::class);
        $collection = new RuleCollection([$rule1, $rule2]);

        $this->assertCount(2, $collection);
    }

    public function testAll(): void
    {
        $rule1 = $this->createMock(UrlRuleInterface::class);
        $rule2 = $this->createMock(UrlRuleInterface::class);
        $collection = new RuleCollection([$rule1, $rule2]);

        $this->assertSame([$rule1, $rule2], $collection->all());
    }
}
