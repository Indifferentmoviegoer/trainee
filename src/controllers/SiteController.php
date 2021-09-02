<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * Контроллер главной страницы
 */
class SiteController extends Controller
{
    /**
     * @return string[][]
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
