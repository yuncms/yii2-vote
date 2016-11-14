<?php

namespace yuncms\vote\migrations;

use yii\db\Migration;

class M161114063039Create_voew_rating_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%vote_rating}}', [
            'id' => $this->primaryKey(),
            'source_type' => $this->string(100)->notNull(),
            'source_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->defaultValue(null),
            'user_ip'=>$this->string()->notNull(),
            'value' => $this->boolean()->notNull(),
            'date' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex('rating_model_id_target_id', '{{%vote_rating}}', ['source_type','source_id'], false);
        $this->createIndex('rating_user_id', '{{%vote_rating}}', 'user_id', false);
        $this->createIndex('rating_user_ip', '{{%vote_rating}}', 'user_ip', false);
    }

    public function down()
    {
        $this->dropTable('{{%vote_rating}}');
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
