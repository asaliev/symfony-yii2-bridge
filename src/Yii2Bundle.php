<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge;

use Asaliev\Yii2Bridge\DependencyInjection\Yii2Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class Yii2Bundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new Yii2Extension();
    }
}
