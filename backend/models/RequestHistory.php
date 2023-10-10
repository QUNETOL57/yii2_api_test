<?php

namespace backend\models;

use common\models\User;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "request_history".
 *
 * @property int $id
 * @property int $request_id ИД заявки
 * @property int $old_status Старый статус
 * @property int $new_status Новый статус
 * @property int|null $created_by Кто создал
 * @property string|null $created_at Дата создания
 *
 * @property User $createdBy
 * @property Request $request
 */
class RequestHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'request_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['request_id', 'old_status', 'new_status'], 'required'],
            [['request_id', 'old_status', 'new_status', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => Request::class, 'targetAttribute' => ['request_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'request_id' => 'ИД заявки',
            'old_status' => 'Старый статус',
            'new_status' => 'Новый статус',
            'created_by' => 'Кто создал',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery
     */
    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Request]].
     *
     * @return ActiveQuery
     */
    public function getRequest(): ActiveQuery
    {
        return $this->hasOne(Request::class, ['id' => 'request_id']);
    }

    /**
     * @param int $request_id
     * @param int $old_status
     * @param int $new_status
     * @return void
     */
    public static function saveHistory(int $request_id, int $old_status, int $new_status): void
    {
        $model = new self;
        $model->request_id = $request_id;
        $model->old_status = $old_status;
        $model->new_status = $new_status;
        $model->save();
    }
}
