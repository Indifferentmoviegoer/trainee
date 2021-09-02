<?php

use app\models\Manager;
use app\models\Request;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\repositories\RequestRepository;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить заявку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'created_at:datetime',
            //'updated_at:datetime',
            'email:email',
            'phone',
            [
                'attribute' => 'manager_id',
                'filter' => Manager::getList(),
                'value' => function (Request $request) {
                    return $request->manager ? $request->manager->name : null;
                }
            ],
            [
                'attribute' => 'Предыдущая заявка',
                'value' => function ($data) {

                    $request = RequestRepository::getPreviouslyRequest($data);
                    $url = Url::toRoute(['request/view', 'id' => $request->id]);

                    $previouslyRequest = '-';
                    if(!empty($request)){
                        $previouslyRequest = 'Заявка №' . $request->id;
                        return Html::a($previouslyRequest, $url);
                    }

                    return Html::tag('p', $previouslyRequest);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    if($data->status){
                        return '<span class="text-success">Закрыта</span>';
                    }
                    return '<span class="text-danger">Открыта</span>';
                },
                'format' => 'raw'
            ],
            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a('Просмотр', $url, [
                            'class' => 'btn btn-primary',
                        ]);
                    },
                ],
                'contentOptions' => ['style' => 'width:1px'],
            ],
            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('Редактировать', $url, [
                            'class' => 'btn btn-primary',
                        ]);
                    },
                ],
                'contentOptions' => ['style' => 'width:1px'],
            ],
        ],
    ]); ?>

</div>
