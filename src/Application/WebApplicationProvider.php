<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Application;

use Asaliev\Yii2Bridge\Config\Config;
use Asaliev\Yii2Bridge\Event\WebApplicationBeforeInitializeEvent;
use Asaliev\Yii2Bridge\Event\WebApplicationInitializedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use yii\base\Application;
use yii\web\Application as WebApplication;

final class WebApplicationProvider implements ApplicationProviderInterface
{
    /**
     * @var Config Yii2 config
     */
    private Config $config;

    /**
     * @var EventDispatcherInterface Event dispatcher
     */
    private EventDispatcherInterface $dispatcher;

    /**
     * Class constructor
     *
     * @param Config $config Yii2 config
     * @param EventDispatcherInterface $dispatcher Router instance
     */
    public function __construct(Config $config, EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function provide(): Application
    {
        $event = new WebApplicationBeforeInitializeEvent($this->config->all());
        $this->dispatcher->dispatch($event);

        $config = $event->getConfig();
        $webApplication = new WebApplication($config);

        $event = new WebApplicationInitializedEvent($webApplication, $config);
        $this->dispatcher->dispatch($event);

        return $webApplication;
    }
}
