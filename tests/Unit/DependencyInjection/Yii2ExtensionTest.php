<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\DependencyInjection;

use Asaliev\Yii2Bridge\Application\ApplicationProviderInterface;
use Asaliev\Yii2Bridge\Application\ContainerFactory;
use Asaliev\Yii2Bridge\Config\Config;
use Asaliev\Yii2Bridge\Config\ConfigFactory;
use Asaliev\Yii2Bridge\DependencyInjection\Yii2Extension;
use Asaliev\Yii2Bridge\EventListener\YiiContainerConfigListener;
use Asaliev\Yii2Bridge\Http\ResponseAdapterInterface;
use Asaliev\Yii2Bridge\Routing\Adapters\RouteAdapterInterface;
use Asaliev\Yii2Bridge\Routing\RoutesLoader;
use Asaliev\Yii2Bridge\Routing\RuleCollection;
use Asaliev\Yii2Bridge\Routing\RuleCollectionFactory;
use Asaliev\Yii2Bridge\Routing\RuleCollectionInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use yii\base\Application as YiiBaseApplication;
use yii\di\Container as YiiContainer;

class Yii2ExtensionTest extends TestCase
{
    public function testLoadRegistersConfigServiceWithValidPath(): void
    {
        $file = TEST_APP_DIR . '/config/web.php';

        $container = new ContainerBuilder();
        $extension = new Yii2Extension();

        $extension->load([['web_config_path' => $file]], $container);

        $this->assertTrue($container->has(Config::class));
        $definition = $container->getDefinition(Config::class);
        $this->assertEquals([new Reference(ConfigFactory::class), 'create'], $definition->getFactory());
        $this->assertEquals([$file], $definition->getArguments());
    }

    public function testLoadDoesNotOverrideYiiContainerWhenClassIsNull(): void
    {
        $container = new ContainerBuilder();
        $extension = new Yii2Extension();

        $extension->load([], $container);

        $this->assertTrue($container->has(YiiContainer::class));
        $definition = $container->getDefinition(YiiContainer::class);
        $this->assertEquals([new Reference(ContainerFactory::class), 'createContainer'], $definition->getFactory());
    }

    public function testLoadRegistersApplicationServices(): void
    {
        $container = new ContainerBuilder();
        $extension = new Yii2Extension();

        $extension->load([], $container);

        $this->assertTrue($container->has(ApplicationProviderInterface::class));
        $this->assertTrue($container->has(YiiBaseApplication::class));
        $this->assertTrue($container->has(ResponseAdapterInterface::class));
        $this->assertTrue($container->has('yii2.dispatcher.controller'));
        $this->assertTrue($container->getDefinition('yii2.dispatcher.controller')->hasTag('controller.service_arguments'));
        $this->assertTrue($container->has('yii2.dispatcher.handler'));
        $this->assertTrue($container->getDefinition('yii2.dispatcher.handler')->hasTag('messenger.message_handler'));
    }

    public function testLoadRegistersRoutingServices(): void
    {
        $container = new ContainerBuilder();
        $extension = new Yii2Extension();

        $extension->load([], $container);

        $this->assertTrue($container->has(RuleCollectionFactory::class));
        $this->assertTrue($container->has(RuleCollectionInterface::class));
        $this->assertTrue($container->has(RuleCollection::class));
        $this->assertTrue($container->has(RouteAdapterInterface::class));
        $this->assertTrue($container->has(RoutesLoader::class));
        $this->assertTrue($container->getDefinition(RoutesLoader::class)->hasTag('routing.loader'));
    }

    public function testLoadRegistersListenerServices(): void
    {
        $container = new ContainerBuilder();
        $extension = new Yii2Extension();

        $extension->load([], $container);

        $this->assertTrue($container->has(YiiContainerConfigListener::class));
    }
}
