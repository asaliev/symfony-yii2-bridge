<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\EventListener;

use Asaliev\Yii2Bridge\Event\WebApplicationBeforeInitializeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Yii;
use yii\di\Container;

final class YiiContainerConfigListener implements EventSubscriberInterface
{
    /**
     * @var Container|null Optional Yii container
     */
    private ?Container $container;

    /**
     * Class constructor
     *
     * @param Container|null $container
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WebApplicationBeforeInitializeEvent::class => 'onConfigProcessing',
        ];
    }

    /**
     * Processes the Yii2 config
     *
     * @param WebApplicationBeforeInitializeEvent $event
     * @return void
     */
    public function onConfigProcessing(WebApplicationBeforeInitializeEvent $event): void
    {
        if ($this->container !== null) {
            // If the Yii2 container is injected, it means that it has already been configured by the user, and we
            // don't want the Yii2 Application instance to override it
            $yiiConfig = $event->getConfig();
            unset($yiiConfig['container']);
            $event->setConfig($yiiConfig);

            Yii::$container = $this->container;
        }
    }
}
