<?php

namespace Asaliev\Tests\Yii2Bridge\Unit;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Yii;

class TestCase extends BaseTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->destroyYiiApplication();

        parent::tearDown();
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     *
     * @link https://github.com/yiisoft/yii2/blob/master/tests/TestCase.php
     * @return void
     */
    protected function destroyYiiApplication(): void
    {
        Yii::$app = null; // @phpstan-ignore-line
        Yii::$container = null; // @phpstan-ignore-line
    }
}
