<?php

namespace app\models;

use yii\data\ActiveDataProvider;

/**
 * Поиск по заявкам
 */
class RequestSearch extends Request
{
    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['id', 'manager_id'], 'integer'],
            [['email', 'phone'], 'safe'],
            ['status', 'boolean'],
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = Request::find();
        $query->with(['manager']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'manager_id' => $this->manager_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
