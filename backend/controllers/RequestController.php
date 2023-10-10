<?php

namespace backend\controllers;

use backend\models\Request;
use backend\models\search\RequestSearch;
use Swagger\Annotations as SWG;
use Yii;
use yii\rest\ActiveController;


class RequestController extends ActiveController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
    ];

    public $modelClass = Request::class;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }


    /**
     * @SWG\Get(
     *     path="/requests",
     *     summary="Получение заявок с фильтрацией",
     *     tags={"Заявки"},
     *     description="Возвращает список заявок с историей",
     *     @SWG\Parameter(name="id", in="query", description="id заявки", required=false, type="integer"),
     *     @SWG\Parameter(name="manager_id", in="query", type="integer", required=false, description="ИД менеджера"),
     *     @SWG\Parameter(name="description", in="query", type="string", required=false, format="textarea", description="Описание заявки"),
     *     @SWG\Parameter(name="status", in="query", type="integer", enum={0,1,2}, required=false, description="Статус заявки"),
     *     @SWG\Parameter(name="comment", in="query", type="string", format="textarea", required=false, description="Комментарий заявки"),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(ref="#/definitions/Request")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Request not found"
     *     )
     * )
     */
    public function actionIndex()
    {
        $requestParams =  Yii::$app->getRequest()->getQueryParams();

        $model = (new RequestSearch())->search($requestParams);
        return $model;
    }


    /**
     * @SWG\Post(
     *     path="/requests",
     *     summary="Создание заявки",
     *     tags={"Заявки"},
     *     @SWG\Parameter(name="manager_id", in="formData", type="integer", required=true, description="ИД менеджера"),
     *     @SWG\Parameter(name="description", in="formData", type="string", format="textarea", required=true, description="Описание заявки"),
     *     @SWG\Parameter(name="status", in="formData", type="integer", default="0",  enum={0,1,2}, required=true, description="Статус заявки"),
     *     @SWG\Response(
     *         response=201,
     *         description="Успешное создание",
     *         @SWG\Schema(ref="#/definitions/Request")
     *     )
     * )
     */
    public function actionCreate()
    {
        return parent::actionCreate();
    }

    /**
     * @SWG\Put(
     *     path="/requests/{id}",
     *     summary="Обновление заявки",
     *     tags={"Заявки"},
     *     description="Обновляет заявку по id",
     *     produces={"application/json"},
     *     @SWG\Parameter(name="id", in="path", description="id заявки", required=true, type="integer"),
     *     @SWG\Parameter(name="manager_id", in="formData", type="integer", description="ИД менеджера"),
     *     @SWG\Parameter(name="description", in="formData", type="string", format="textarea", description="Описание заявки"),
     *     @SWG\Parameter(name="status", in="formData", type="integer", enum={0,1,2}, description="Статус заявки"),
     *     @SWG\Parameter(name="comment", in="formData", type="string", format="textarea", description="Комментарий заявки"),
     *     @SWG\Response(
     *         response=200,
     *         description="Успешное обновление",
     *         @SWG\Schema(ref="#/definitions/Request")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Заявка не найдена"
     *     )
     * )
     */
    public function actionUpdate()
    {
        return parent::actionUpdate();
    }


    /**
     * @SWG\Delete(
     *     path="/requests/{id}",
     *     summary="Удаление заявки",
     *     tags={"Заявки"},
     *     description="Удаляет заявку по id",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id заявки",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="Успешное удаление"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Заявка не найдена"
     *     )
     * )
     */
    public function actionDelete()
    {
        return parent::actionDelete();
    }

}