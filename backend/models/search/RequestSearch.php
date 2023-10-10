<?php

namespace backend\models\search;


use backend\enums\UserRoleEnum;
use backend\models\Request;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class RequestSearch extends Request
{
    /**
     * @return array
     */
    public function fields(): array
    {
        return ArrayHelper::merge(parent::fields(), [
            'history' => function (Request $model) {
                return $model->history;
            },
        ]);
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws \Exception
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = static::find()
            ->alias('t')
            ->joinWith(['history']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, '');

        $this->addFieldsFilter($query);

        return $this->addFieldsSort($dataProvider);
    }


    /**
     * @param ActiveQuery $query
     * @throws \Exception
     */
    public function addFieldsFilter(ActiveQuery $query)
    {
        $user = User::findIdentity(Yii::$app->user->id);

        // Пользователям показываем только те заявки, которые были созданы ими
        if ($user->role === UserRoleEnum::User->value) {
            $query->andFilterWhere([
                't.created_by' => $user->id,
            ]);
        }

        // Менеджерам показываем только те заявки, в которых они значатся
        if ($user->role === UserRoleEnum::Manager->value) {
            $query->andFilterWhere([
                't.manager_id' => $user->id,
            ]);
        }

        $query->andFilterWhere([
            't.id' => $this->id,
            't.status' => $this->status,
        ]);

        $query->andFilterWhere(
            ['like', 't.description', $this->description]
        );

        $query->andFilterWhere(
            ['like', 't.comment', $this->comment]
        );
    }


    /**
     * @param ActiveDataProvider $dataProvider
     * @return ActiveDataProvider
     */
    public function addFieldsSort(ActiveDataProvider $dataProvider): ActiveDataProvider
    {
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'created_at',
                'updated_at',
                'status',
            ],
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
        ]);

        return $dataProvider;
    }
}