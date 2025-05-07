<?php

namespace Asaliev\Yii2Bridge\Routing\Adapters;

use Asaliev\Yii2Bridge\Controller\DispatcherController;
use Symfony\Component\Routing\Route;
use yii\web\UrlRule;
use yii\web\UrlRuleInterface;

final class UrlRuleAdapter implements RouteAdapterInterface
{
    /**
     * @var callable
     */
    protected $controllerCallback = ['yii2.dispatcher.controller', 'run'];

    /**
     * @inheritDoc
     */
    public function convert(UrlRuleInterface $yiiRule): Route
    {
        /** @var UrlRule $yiiRule */
        // Extract pattern (remove Yii2-specific regex markers if necessary)
        $pattern = preg_replace('/^#\^(.*?)\$#u$/', '$1', $yiiRule->pattern);

        // Convert Yii2 placeholders with regex constraints to Symfony format
        $requirements = [];
        $pattern = preg_replace_callback('/\(\?P<(\w+)>([^)]+)\)/', function ($matches) use (&$requirements) {
            $paramName = $matches[1];
            $regex = $matches[2];
            $requirements[$paramName] = $regex;

            return '{' . $paramName . '}';
        }, $pattern);

        // Extract methods if defined
        $methods = $yiiRule->verb ? (array) $yiiRule->verb : [];

        // Define controller handling this route
        $yiiPatternName = 'yii_route_' . md5($yiiRule->pattern);
        $defaults = array_merge($yiiRule->defaults, [
            '_controller' => $this->controllerCallback,
            'yii2_rule_name' => $yiiPatternName,
            'yii2_rule_definition' => serialize($yiiRule),
        ]);

        return new Route(
            $pattern,
            $defaults,
            $requirements,
            [],
            $yiiRule->host ?: '',
            [],
            $methods
        );
    }
}
