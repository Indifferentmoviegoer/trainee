<?php

namespace app\repositories;

use app\models\Request;
use DateTime;
use yii\db\ActiveRecord;

/**
 * Репозиторий для работы с моделью Request
 */
class RequestRepository
{
    /**
     * @param Request $data
     *
     * @return array|ActiveRecord|null
     */
    public static function getPreviouslyRequest(Request $data)
    {
        $monthDateTime = (new DateTime())->modify('-30 days');
        $searchMonth = $monthDateTime->format('Y-m-d');

        return Request::find()
            ->select(['id', 'manager_id'])
            ->where(['<', 'id', $data->id])
            ->andWhere('created_at<=NOW()')
            ->andWhere(['>=', 'created_at', $searchMonth])
            ->andWhere(
                ['or',
                    ['email' => $data->email],
                    ['phone' => $data->phone]
                ]
            )
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }
}