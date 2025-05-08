<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Application;

use Asaliev\Yii2Bridge\Config\Config;
use Asaliev\Yii2Bridge\Exception\ContainerException;
use Asaliev\Yii2Bridge\Exception\DispatcherException;
use yii\di\Container as YiiContainer;

final class ContainerFactory
{
    /**
     * @var Config Yii2 config
     */
    private Config $config;

    /**
     * Class constructor
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Creates an instance of Yii2 container.
     *
     * @param class-string|null $class
     * @return YiiContainer
     * @throws DispatcherException When the class does not exist or is not a subclass of {@see \yii\di\Container}
     */
    public function createContainer(?string $class = null): YiiContainer
    {
        $class = $class ?? YiiContainer::class;
        if (!class_exists($class)) {
            throw new ContainerException("Class $class does not exist.");
        }

        if ($class !== YiiContainer::class && !is_subclass_of($class, YiiContainer::class)) {
            throw new ContainerException('Container class must be an instance of yii\di\Container');
        }

        return new $class($this->config->getOrFail('container'));
    }
}
