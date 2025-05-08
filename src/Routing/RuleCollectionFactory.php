<?php

namespace Asaliev\Yii2Bridge\Routing;

use yii\base\Application;

class RuleCollectionFactory
{
    /**
     * @var Application Url Manager instance
     */
    private Application $app;

    /**
     * Class constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Creates a new RuleCollection instance.
     *
     * @return RuleCollection
     */
    public function create(): RuleCollection
    {
        $collection = new RuleCollection();
        foreach ($this->app->getUrlManager()->rules as $yiiRule) {
            $index = 'yii_route_' . md5($yiiRule->pattern);
            $collection[$index] = $yiiRule;
        }

        return $collection;
    }
}
