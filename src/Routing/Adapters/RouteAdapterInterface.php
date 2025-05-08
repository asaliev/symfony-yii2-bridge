<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Routing\Adapters;

use Symfony\Component\Routing\Route;
use yii\web\UrlRuleInterface;

interface RouteAdapterInterface
{
    /**
     * Converts a Yii2 Rule to a Symfony Route.
     *
     * @param UrlRuleInterface $yiiRule The Yii2 URL rule to convert
     * @return Route The converted Symfony Route
     */
    public function convert(UrlRuleInterface $yiiRule): Route;
}
