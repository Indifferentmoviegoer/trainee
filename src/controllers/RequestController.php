<?php

namespace app\controllers;

use app\repositories\ManagerRepository;
use app\repositories\RequestRepository;
use Yii;
use app\models\Request;
use app\models\RequestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер заявок
 */
class RequestController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new RequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Request();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $previouslyRequest = RequestRepository::getPreviouslyRequest($model);

            if ($previouslyRequest && $previouslyRequest->manager->is_works) {
                $model->manager_id = $previouslyRequest->manager_id;
            } else {
                $requests = [];
                $managers = ManagerRepository::getList();
                if ($managers) {
                    foreach ($managers as $manager) {
                        $requests[$manager->id][] += $manager->getRequests()->count();
                    }
                    $model->manager_id = array_search(min($requests), $requests);
                }
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     *
     * @return Request|null
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): ?Request
    {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
