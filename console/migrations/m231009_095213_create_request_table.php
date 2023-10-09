<?php

use yii\db\Migration;

/**
 * Class m231009_095213_create_table_requests
 */
class m231009_095213_create_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('request',
            [
                'id' => $this->primaryKey(),
                'description' => $this->text()->notNull()->comment('Описание заявки'),
                'manager_id' => $this->integer()->notNull()->comment('ИД менеджера'),
                'status' => $this->smallInteger(10)->notNull()->comment('Статус'),
                'comment' => $this->text()->comment('Комментарий'),
                'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
                'updated_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата изменения'),
            ]
        );

        $this->addCommentOnTable('request', 'Заявки');

        $this->addForeignKey('fk_request_user', 'request', 'manager_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_request_user', 'request');
        $this->dropTable('request');
    }
}
