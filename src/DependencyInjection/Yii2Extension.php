<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\DependencyInjection;

use Asaliev\Yii2Bridge\Application\ApplicationProviderInterface;
use Asaliev\Yii2Bridge\Application\ContainerFactory;
use Asaliev\Yii2Bridge\Application\PsrPreferredContainerAdapter;
use Asaliev\Yii2Bridge\Application\WebApplicationProvider;
use Asaliev\Yii2Bridge\Config\Config;
use Asaliev\Yii2Bridge\Config\ConfigFactory;
use Asaliev\Yii2Bridge\Config\Loader\PhpArrayLoader;
use Asaliev\Yii2Bridge\Controller\DispatcherController;
use Asaliev\Yii2Bridge\EventListener\YiiContainerConfigListener;
use Asaliev\Yii2Bridge\Http\ResponseAdapter;
use Asaliev\Yii2Bridge\Http\ResponseAdapterInterface;
use Asaliev\Yii2Bridge\Messages\Handlers\YiiDispatcherHandler;
use Asaliev\Yii2Bridge\Routing\Adapters\RouteAdapterInterface;
use Asaliev\Yii2Bridge\Routing\Adapters\UrlRuleAdapter;
use Asaliev\Yii2Bridge\Routing\RoutesLoader;
use Asaliev\Yii2Bridge\Routing\RuleCollection;
use Asaliev\Yii2Bridge\Routing\RuleCollectionFactory;
use Asaliev\Yii2Bridge\Routing\RuleCollectionInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use yii\base\Application as YiiBaseApplication;
use yii\di\Container as YiiContainer;

class Yii2Extension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Load extension config
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Register services
        $this->registerConfig($container, $config['web_config_path']);
        $this->registerYiiContainer($container, $config['override_yii_container_class']);
        $this->registerYiiApplication($container);
        $this->registerYiiApplicationRunner($container, $config['messenger_bus']);
        $this->registerRouting($container);
        $this->registerListeners($container);
    }

    /**
     * Registers Yii2 config as a service
     *
     * @param ContainerBuilder $container
     * @param string $yiiConfigPath
     * @return void
     */
    private function registerConfig(ContainerBuilder $container, string $yiiConfigPath): void
    {
        $container->register(PhpArrayLoader::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(ConfigFactory::class)
            ->setArguments([new Reference(PhpArrayLoader::class)])
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(Config::class)
            ->setFactory([new Reference(ConfigFactory::class), 'create'])
            ->setShared(true)
            ->setArguments([$yiiConfigPath]);
        $container->setAlias('yii2.config', Config::class);
    }

    /**
     * Registers the Yii container
     *
     * @param ContainerBuilder $container
     * @param string|null $containerClass
     * @return void
     */
    private function registerYiiContainer(ContainerBuilder $container, ?string $containerClass): void
    {
        $container->register(ContainerFactory::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(PsrPreferredContainerAdapter::class)
            ->setArguments([new Reference(ContainerInterface::class), new Reference('yii2.config')])
            ->setAutowired(true)
            ->setAutoconfigured(true);

        if (!empty($containerClass)) {
            $container->setAlias(YiiContainer::class, $containerClass);
        } else {
            $container->register(YiiContainer::class)
                ->setFactory([new Reference(ContainerFactory::class), 'createContainer'])
                ->setArguments([YiiContainer::class])
                ->setAutowired(true)
                ->setAutoconfigured(true);
        }
    }

    /**
     * Registers the Yii2 application class
     *
     * @param ContainerBuilder $container
     * @return void
     */
    private function registerYiiApplication(ContainerBuilder $container): void
    {
        $container
            ->register(ApplicationProviderInterface::class, WebApplicationProvider::class)
            ->setArguments([new Reference('yii2.config')])
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container
            ->register(YiiBaseApplication::class)
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->setFactory([new Reference(ApplicationProviderInterface::class), 'provide']);
    }

    /**
     * Registers the message bus handler and controller for running Yii applications
     *
     * @param ContainerBuilder $container
     * @param string $messageBus
     * @return void
     */
    private function registerYiiApplicationRunner(ContainerBuilder $container, string $messageBus): void
    {
        $container->register(ResponseAdapterInterface::class, ResponseAdapter::class);
        $container->register('yii2.dispatcher.controller', DispatcherController::class)
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->setArgument('$messageBus', new Reference($messageBus))
            ->addTag('controller.service_arguments');

        $container->register('yii2.dispatcher.handler', YiiDispatcherHandler::class)
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->addTag('messenger.message_handler');
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    private function registerRouting(ContainerBuilder $container): void
    {
        $container
            ->register(RuleCollectionFactory::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container
            ->register(RuleCollection::class)
            ->setFactory([new Reference(RuleCollectionFactory::class), 'create']);

        $container->setAlias(RuleCollectionInterface::class, RuleCollection::class);

        $container->setDefinition(RouteAdapterInterface::class, new Definition(UrlRuleAdapter::class));
        $container->register(RoutesLoader::class)
            ->setArguments([
                new Reference(RuleCollection::class),
                new Reference(RouteAdapterInterface::class),
            ])
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->addTag('routing.loader');
    }

    /**
     * Register listeners
     *
     * @param ContainerBuilder $container
     * @return void
     */
    private function registerListeners(ContainerBuilder $container): void
    {
        $container
            ->register(YiiContainerConfigListener::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);
    }
}
