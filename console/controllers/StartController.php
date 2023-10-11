<?php

namespace console\controllers;

use backend\enums\UserRoleEnum;
use common\models\User;
use DateTime;
use Faker\Factory;
use Yii;
use yii\console\Controller;

class StartController extends Controller
{
    /**
     * @return void
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionCreateUsers(): void
    {
        $faker = Factory::create();
        $password = '12345';
        $dateTime = (new DateTime())->getTimestamp();

        $data = [];

        // Создание Админа
        $data[] = [
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash($password),
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'email' => $faker->email,
            'status' => User::STATUS_ACTIVE,
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
            'verification_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'role' => UserRoleEnum::Admin->value
        ];

        // Создание Менеджеров
        for ($i = 1; $i <= 2; $i++) {
            $data[] = [
                'username' => 'manager' . $i,
                'auth_key' => Yii::$app->security->generateRandomString(),
                'password_hash' => Yii::$app->security->generatePasswordHash($password),
                'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
                'email' => $faker->email,
                'status' => User::STATUS_ACTIVE,
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
                'verification_token' => Yii::$app->security->generateRandomString() . '_' . time(),
                'role' => UserRoleEnum::Manager->value
            ];
        }

        // Создание Юзеров
        for ($i = 1; $i <= 2; $i++) {
            $data[] = [
                'username' => 'user' . $i,
                'auth_key' => Yii::$app->security->generateRandomString(),
                'password_hash' => Yii::$app->security->generatePasswordHash($password),
                'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
                'email' => $faker->email,
                'status' => User::STATUS_ACTIVE,
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
                'verification_token' => Yii::$app->security->generateRandomString() . '_' . time(),
                'role' => UserRoleEnum::User->value
            ];
        }

        Yii::$app->db->createCommand()->batchInsert('user', [
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email',
            'status',
            'created_at',
            'updated_at',
            'verification_token',
            'role'
        ], $data)->execute();

    }
}