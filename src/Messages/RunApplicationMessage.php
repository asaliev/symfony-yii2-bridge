<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Messages;

use Symfony\Component\HttpFoundation\Request;
use yii\base\Application;

final class RunApplicationMessage
{
    /**
     * @var Application
     */
    private Application $app;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * Class constructor
     *
     * @param Request $request The Symfony request object
     * @param Application $app The Yii2 application instance
     */
    public function __construct(Request $request, Application $app)
    {
        $this->request = $request;
        $this->app = $app;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Gets the Yii2 application instance.
     *
     * @return Application The Yii2 application instance
     */
    public function getApplication(): Application
    {
        return $this->app;
    }
}
