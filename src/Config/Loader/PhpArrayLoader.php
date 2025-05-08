<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Config\Loader;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Config\Loader\Loader;

final class PhpArrayLoader extends Loader
{
    /**
     * {@inheritDoc}
     */
    public function load($resource, ?string $type = null): array
    {
        if (!file_exists($resource)) {
            throw new InvalidArgumentException(sprintf('File does not exist: %s', $resource));
        }

        $result = require $resource;

        if (!is_array($result)) {
            throw new RuntimeException("Config file must return an array: $resource");
        }

        return $result;
    }

    /**
     * Checks if the loader supports the given resource.
     *
     * @param string $resource The resource to check
     * @param string|null $type The resource type
     * @return bool True if the loader supports the resource, false otherwise
     */
    public function supports($resource, ?string $type = null): bool
    {
        return 'php' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
