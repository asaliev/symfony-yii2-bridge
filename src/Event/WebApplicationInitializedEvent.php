<?php

namespace Asaliev\Yii2Bridge\Event;

use Symfony\Contracts\EventDispatcher\Event;
use yii\base\Application;
use yii\web\UrlRuleInterface;

final class WebApplicationInitializedEvent extends Event
{
    /**
     * @var Application Yii2 application instance
     */
    private Application $app;

    /**
     * @var array<string, mixed> Original Yii2 config
     */
    private array $config;

    /**
     * Class constructor
     *
     * @param Application $app
     * @param array<string, mixed> $config
     */
    public function __construct(Application $app, array $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * Returns the Yii2 application instance
     *
     * @return Application
     */
    public function getApplication(): Application
    {
        return $this->app;
    }

    /**
     * Returns the original Yii2 config
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
