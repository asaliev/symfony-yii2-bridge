<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Application;

use yii\base\Application;
use yii\base\InvalidConfigException;

interface ApplicationProviderInterface
{
    /**
     * Provides an instance of the Yii2 application.
     *
     * @return Application Yii2 application
     * @throws InvalidConfigException
     */
    public function provide(): Application;
}
