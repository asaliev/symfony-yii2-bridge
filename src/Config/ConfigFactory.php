<?php

namespace Asaliev\Yii2Bridge\Config;

use Exception;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Factory method which is used to register the Config service with Symfony
 */
final class ConfigFactory
{
    /**
     * @var LoaderInterface Loader instance
     */
    private LoaderInterface $loader;

    /**
     * Class constructor
     *
     * @param LoaderInterface $loader Loader instance
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Creates a new Config instance from a given filename.
     *
     * @param string $filename
     * @return Config
     * @throws Exception
     */
    public function create(string $filename): Config
    {
        $config = $this->loader->load($filename);

        return new Config($config);
    }
}
