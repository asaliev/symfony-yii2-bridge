<?php

namespace Asaliev\Yii2Bridge\Application;

use Asaliev\Yii2Bridge\Config\Config;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\di\NotInstantiableException;

final class PsrPreferredContainerAdapter extends Container
{
    /**
     * @var ContainerInterface PSR-11 compatible container
     */
    private ContainerInterface $psrContainer;

    /**
     * Class constructor
     *
     * @param ContainerInterface $psrContainer PSR-11 compatible container
     * @param Config $config Optional Yii2 container config
     */
    public function __construct(ContainerInterface $psrContainer, Config $config)
    {
        $this->psrContainer = $psrContainer;

        parent::__construct($config->get('container', []));
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function has($class)
    {
        if ($this->psrContainer->has($class)) {
            return true;
        }

        return parent::has($class);
    }

    /**
     * {@inheritDoc}
     *
     * @return object
     */
    public function get($class, $params = [], $config = [])
    {
        if ($this->psrContainer->has($class)) {
            try {
                return $this->psrContainer->get($class);
            } catch (NotFoundExceptionInterface $e) {
                throw new InvalidConfigException($e->getMessage(), $e->getCode(), $e);
            } catch (ContainerExceptionInterface $e) {
                $class = is_object($class) ? get_class($class) : $class;

                throw new NotInstantiableException($class, $e->getMessage(), $e->getCode(), $e);
            }
        }

        return parent::get($class, $params, $config);
    }
}
