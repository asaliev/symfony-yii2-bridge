<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Http;

use Symfony\Component\HttpFoundation\Response;
use yii\base\Response as YiiResponse;

interface ResponseAdapterInterface
{
    public function toSymfonyResponse(YiiResponse $response, string $output): Response;
}
