<?php

namespace backend\models;

use common\models\User;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "request".
 *
 * @property int $id
 * @property string $description Описание заявки
 * @property int $manager_id ИД менеджера
 * @property int $status Статус
 * @property string|null $comment Комментарий
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 *
 * @property User $manager
 * @property RequestHistory[] $requestHistories
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description', 'manager_id', 'status'], 'required'],
            [['description', 'comment'], 'string'],
            [['manager_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['manager_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'description' => 'Описание заявки',
            'manager_id' => 'ИД менеджера',
            'status' => 'Статус',
            'comment' => 'Комментарий',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
     * Gets query for [[Manager]].
     *
     * @return ActiveQuery
     */
    public function getManager(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'manager_id']);
    }

    /**
     * Gets query for [[RequestHistories]].
     *
     * @return ActiveQuery
     */
    public function getRequestHistories(): ActiveQuery
    {
        return $this->hasMany(RequestHistory::class, ['request_id' => 'id']);
    }
}
