<?php

use yii\db\Migration;

/**
 * Class m231009_095214_create_request_history_table
 */
class m231009_095214_create_request_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('request_history',
            [
                'id' => $this->primaryKey(),
                'request_id' => $this->integer()->notNull()->comment('ИД заявки'),
                'old_status' => $this->smallInteger(10)->notNull()->comment('Старый статус'),
                'new_status' => $this->smallInteger(10)->notNull()->comment('Новый статус'),
                'created_by' => $this->integer()->comment('Кто создал'),
                'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            ]
        );

        $this->addCommentOnTable('request_history', 'История изменения статусов заявок');

        $this->addForeignKey('fk_history_request', 'request_history', 'request_id', 'request', 'id', 'CASCADE');
        $this->addForeignKey('fk_history_user', 'request_history', 'created_by', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_history_request', 'request_history');
        $this->dropForeignKey('fk_history_user', 'request_history');
        $this->dropTable('request_history');
    }
}
