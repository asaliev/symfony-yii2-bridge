<?php

namespace Asaliev\Yii2Bridge\Event;

use Symfony\Contracts\EventDispatcher\Event;
use yii\web\UrlRuleInterface;

final class WebApplicationBeforeInitializeEvent extends Event
{
    /**
     * @var array<string, mixed> Yii2 config
     */
    private array $yiiConfig = [];

    /**
     * Class constructor
     *
     * @param array<string, mixed> $yiiConfig Yii2 config
     */
    public function __construct(array $yiiConfig)
    {
        $this->yiiConfig = $yiiConfig;
    }

    /**
     * Returns the Yii2 config
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->yiiConfig;
    }

    /**
     * Sets the Yii2 config
     *
     * @param array<string, mixed> $config
     * @return void
     */
    public function setConfig(array $config): void
    {
        $this->yiiConfig = $config;
    }
}
