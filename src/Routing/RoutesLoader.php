<?php

namespace Asaliev\Yii2Bridge\Routing;

use Asaliev\Yii2Bridge\Routing\Adapters\RouteAdapterInterface;
use RuntimeException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection as SymfonyRouteCollection;

/**
 * Class RoutesLoader loads Yii2 routes into Symfony's RouteCollection.
 */
class RoutesLoader extends Loader
{
    /**
     * @var string The type of the loader
     */
    public const TYPE = 'yii2_routes';

    /**
     * @var string Internal prefix for the route name
     */
    public const ROUTE_PREFIX = 'yii2_route_';

    /**
     * @var bool Flag to check if the loader has already been loaded
     */
    private bool $isLoaded = false;

    /**
     * @var RuleCollectionInterface
     */
    private RuleCollectionInterface $ruleCollection;

    /**
     * @var RouteAdapterInterface
     */
    private RouteAdapterInterface $routeAdapter;

    /**
     * Class constructor
     *
     * @param RuleCollectionInterface $ruleCollection
     * @param RouteAdapterInterface $routeAdapter
     * @param string|null $env
     */
    public function __construct(
        RuleCollectionInterface $ruleCollection,
        RouteAdapterInterface $routeAdapter,
        ?string $env = null
    ) {
        $this->ruleCollection = $ruleCollection;
        $this->routeAdapter = $routeAdapter;

        parent::__construct($env);
    }

    /**
     * {@inheritDoc}
     */
    public function load($resource, ?string $type = null): SymfonyRouteCollection
    {
        if ($this->isLoaded) {
            throw new RuntimeException('Do not add the "yii2_routes" loader twice.');
        }

        $this->isLoaded = true;
        $routes = new SymfonyRouteCollection();
        foreach ($this->ruleCollection->all() as $rule) {
            $route = $this->routeAdapter->convert($rule);
            $routes->add(self::ROUTE_PREFIX . md5($route->getPath()), $route);
        }

        return $routes;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($resource, ?string $type = null): bool
    {
        return $type === self::TYPE;
    }
}
