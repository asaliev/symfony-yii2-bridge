<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Config;

use InvalidArgumentException;

/**
 * This class holds the Yii2 configuration array and provides methods to access its values.
 */
class Config
{
    /**
     * @var array<string, mixed> The configuration array
     */
    private array $config;

    /**
     * Class constructor
     *
     * @param array<string, mixed> $config The configuration array
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Returns all configuration values.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->config;
    }

    /**
     * Returns the value of a configuration key or null if the key is not found.
     *
     * @param string $key Array key to search for
     * @param mixed $default Default value to return if the key is not found
     * @return null|mixed
     */
    public function get(string $key, $default = null)
    {
        try {
            return $this->getOrFail($key);
        } catch (InvalidArgumentException $e) {
            // @ignoreException
        }

        return $default;
    }

    /**
     * Returns the value of a configuration key or throws an exception if the key is not found.
     *
     * @param string $key Array key to search for
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getOrFail(string $key)
    {
        $keys = explode('.', $key);

        return $this->getRecursiveOrFail($this->config, $keys);
    }

    /**
     * Recursively searches for a key in a nested configuration array and returns its value.
     *
     * @param array<string, mixed> $config Configuration array
     * @param string[] $keys Array keys to search for
     * @return mixed
     * @throws InvalidArgumentException When the key is not found
     */
    private function getRecursiveOrFail(array $config, array $keys)
    {
        $key = array_shift($keys);
        if (!array_key_exists($key, $config)) {
            throw new InvalidArgumentException(sprintf('Key "%s" not found in configuration.', $key));
        }

        if (empty($keys)) {
            return $config[$key];
        }

        return $this->getRecursiveOrFail($config[$key], $keys);
    }
}
