<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\DependencyInjection;

use Asaliev\Yii2Bridge\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testBuildTreeWithDefaultValues(): void
    {
        $configuration = new Configuration();
        $treeBuilder = $configuration->getConfigTreeBuilder();
        $tree = $treeBuilder->buildTree();

        $processedConfig = $tree->finalize([]);

        $this->assertEquals('%kernel.project_dir%/config/web_config.php', $processedConfig['web_config_path']);
        $this->assertNull($processedConfig['override_yii_container_class']);
        $this->assertEquals('messenger.default_bus', $processedConfig['messenger_bus']);
    }

    public function testBuildTreeWithOverridesForDefaultValues(): void
    {
        $configuration = new Configuration();
        $treeBuilder = $configuration->getConfigTreeBuilder();
        $tree = $treeBuilder->buildTree();

        $processedConfig = $tree->finalize([
            'web_config_path' => '/foo/bar/config.php',
            'override_yii_container_class' => 'FooContainerClass',
            'messenger_bus' => 'custom.bus',
        ]);

        $this->assertEquals('/foo/bar/config.php', $processedConfig['web_config_path']);
        $this->assertEquals('FooContainerClass', $processedConfig['override_yii_container_class']);
        $this->assertEquals('custom.bus', $processedConfig['messenger_bus']);
    }
}
