<?php

namespace backend\controllers;

use common\models\User;
use Swagger\Annotations as SWG;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = User::class;

    public function behaviors()
    {
        $behavior['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'auth' => function ($username, $password) {
                return User::findByUsernameAndPassword($username, $password);
            }
        ];
        return ArrayHelper::merge(parent::behaviors(), $behavior);
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
     *     description="Возвращает токен пользователя для Bearer авторизации",
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
     */
    public function actionLogin()
    {
        return ['token' => Yii::$app->user->identity->auth_key];
    }
}