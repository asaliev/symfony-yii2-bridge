<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Http;

use yii\web\Response as YiiResponse;

class HeaderlessResponse extends YiiResponse
{
    /**
     * {@inheritDoc}
     *
     * @return void
     */
    protected function sendHeaders()
    {
    }
}
