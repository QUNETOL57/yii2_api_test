<?php

namespace backend\models;

use backend\enums\RequestStatusEnum;
use common\models\User;
use Swagger\Annotations as SWG;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "request".
 *
 * @SWG\Definition(
 *     definition="Request",
 *     type="object",
 * )
 * @SWG\Property(property="id", type="integer", description="ID заявки"),
 * @SWG\Property(property="description", type="string", description="Описание заявки"),
 * @SWG\Property(property="manager_id", type="integer", description="ИД менеджера"),
 * @SWG\Property(property="status", type="integer", description="Статус заявки"),
 * @SWG\Property(property="comment", type="string", description="Комментарий"),
 * @SWG\Property(property="created_at", type="string", format="date-time", description="Дата создания заявки"),
 * @SWG\Property(property="updated_at", type="string", format="date-time", description="Дата изменения заявки")
 * @SWG\Property(property="created_by", type="integer", description="Кто создал")
 * @SWG\Property(property="updated_by", type="integer", description="Кто обновил")
 *
 * @property int $id
 * @property string $description Описание заявки
 * @property int $manager_id ИД менеджера
 * @property int $status Статус
 * @property string|null $comment Комментарий
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 * @property int $created_by [int]  Кто создал
 * @property int $updated_by [int]  Кто обновил
 *
 * @property User $manager
 * @property RequestHistory[] $history
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
     * @return array
     */
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => TimestampBehavior::class,
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
            [
                'class' => BlameableBehavior::class
            ],
            [
                /** Смена статуса заявки если заявка была в работе у нее был написан комментарий  */
                'class' => AttributeBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_UPDATE => 'comment',
                ],
                'value' => function ($event) {
                    $oldValue = $this->getOldAttribute('status');
                    $newValue = $this->status;
                    if ($this->status == RequestStatusEnum::InWork->value && $this->comment != null) {
                        $this->status = RequestStatusEnum::Resolved->value;
                    }
                    return $this->comment;
                },
            ],
            [
                /** Отражение изменения статуса в историю */
                'class' => AttributeBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_UPDATE => 'status',
                ],
                'value' => function ($event) {
                    $oldValue = $this->getOldAttribute('status');
                    $newValue = $this->status;
                    if ($oldValue != $newValue) {
                        RequestHistory::saveHistory($this->id, $oldValue, $newValue);
                    }
                    return $this->status;
                },
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description', 'manager_id', 'status'], 'required'],
            [['description', 'comment'], 'string'],
            [['manager_id', 'status', 'id'], 'integer'],
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
    public function getHistory(): ActiveQuery
    {
        return $this->hasMany(RequestHistory::class, ['request_id' => 'id']);
    }
}
