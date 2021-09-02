<?php

namespace app\repositories;

use app\models\Manager;

/**
 * Репозиторий для работы с моделью Manager
 */
class ManagerRepository
{
    /**
     * @return array
     */
    public static function getList(): array
    {
        return Manager::find()
            ->where(['is_works' => true])
            ->all();
    }
}