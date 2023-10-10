<?php

namespace backend\controllers;

use common\models\User;
use Swagger\Annotations as SWG;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\UnauthorizedHttpException;

class UserController extends ActiveController
{
    public $modelClass = User::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'auth' => function ($username, $password) {
                return User::findByUsernameAndPassword($username, $password);
            }
        ];
        return $behaviors;
    }


    /**
     * @SWG\SecurityScheme(
     *   securityDefinition="basicAuth",
     *   type="basic"
     * )
     * @SWG\Get(
     *     security={{"basicAuth":{}}},
     *     path="/user/login",
     *     summary="Получение токена",
     *     tags={"Пользователи"},
     *     description="Возвращает список заявок с историей",
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Request not found"
     *     )
     * )
     * @throws UnauthorizedHttpException
     */
    public function actionLogin()
    {
        return ['token' => Yii::$app->user->identity->auth_key];
    }
}