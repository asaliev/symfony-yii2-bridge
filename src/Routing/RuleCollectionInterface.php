<?php

namespace Asaliev\Yii2Bridge\Routing;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use yii\web\UrlRuleInterface;

/**
 * Contains Yii2 URL rules.
 *
 * @extends ArrayAccess<int|string, UrlRuleInterface>
 * @extends IteratorAggregate<int|string, UrlRuleInterface>
 */
interface RuleCollectionInterface extends IteratorAggregate, ArrayAccess, Countable
{
    /**
     * Returns all Yii2 URL rules.
     *
     * @return UrlRuleInterface[]
     */
    public function all(): array;
}
