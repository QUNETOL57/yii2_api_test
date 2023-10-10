<?php

use yii\db\Migration;

/**
 * Class m231010_105214_add_columns_for_request_table
 */
class m231010_105214_add_columns_for_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('request', 'created_by', $this->integer()->comment('Кто создал'));
        $this->addColumn('request', 'updated_by', $this->integer()->comment('Кто обновил'));

        $this->addForeignKey('fk_request_created_by_user_id', 'request', 'created_by', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk_request_updated_by_user_id', 'request', 'updated_by', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_request_created_by_user_id', 'request');
        $this->dropForeignKey('fk_request_updated_by_user_id', 'request');
        $this->dropColumn('request', 'created_by');
        $this->dropColumn('request', 'updated_by');
    }
}
