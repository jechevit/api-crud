<?php

namespace app\controllers\behaviors;

use Yii;
use yii\base\ActionFilter;

class DebugBehavior extends ActionFilter
{
    private float $startTime;
    private float $startMemory;

    public function beforeAction($action): bool
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();

        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $executionTime = ($endTime - $this->startTime) * 1000;
        $memoryUsage = ($endMemory - $this->startMemory) / 1024;

        Yii::$app->response->headers->set('X-Debug-Time', number_format($executionTime, 2) . ' ms');
        Yii::$app->response->headers->set('X-Debug-Memory', number_format($memoryUsage, 2) . ' KB');

        return parent::afterAction($action, $result);
    }
}