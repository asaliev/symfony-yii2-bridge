<?php

namespace Asaliev\Yii2Bridge\Routing;

use ArrayIterator;
use InvalidArgumentException;
use Traversable;
use yii\web\UrlRuleInterface;

final class RuleCollection implements RuleCollectionInterface
{
    /**
     * @var UrlRuleInterface[]
     */
    private array $rules;

    /**
     * Class constructor
     *
     * @param UrlRuleInterface[] $rules
     */
    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->rules);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->rules[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset): ?UrlRuleInterface
    {
        return $this->rules[$offset] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof UrlRuleInterface) {
            throw new InvalidArgumentException('Value must implement UrlRuleInterface.');
        }

        if ($offset === null) {
            $this->rules[] = $value;
        } else {
            $this->rules[$offset] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->rules[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->rules);
    }

    /**
     * {@inheritDoc}
     */
    public function all(): array
    {
        return $this->rules;
    }
}
