<?php

namespace yuncms\vote\migrations;

use yii\db\Migration;

class M161114063102Create_vote_aggregate_rating_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%vote_aggregate_rating}}', [
            'id' => $this->primaryKey(),
            'model_type' => $this->string(100)->notNull(),
            'model_id' => $this->integer()->notNull(),
            'likes' => $this->integer()->notNull(),
            'dislikes' => $this->integer()->notNull(),
            'rating' => $this->decimal(3,2)->unsigned()->notNull()
        ], $tableOptions);
        $this->createIndex('aggregate_model_type_model_id', '{{%vote_aggregate_rating}}', ['model_type','model_id'], true);
    }

    public function down()
    {
        $this->dropTable('{{%vote_aggregate_rating}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
