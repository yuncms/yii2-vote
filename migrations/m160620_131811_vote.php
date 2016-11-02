<?php

use yii\db\Migration;

/**
 * @author Alexander Kononenko <contact@hauntd.me>
 */
class m160620_131811_vote extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%vote}}', [
            'id' => $this->primaryKey(),
            'entity' => $this->integer()->unsigned()->notNull(),
            'target_id' => $this->integer()->notNull(),
            'user_id' => $this->integer(),
            'user_ip' => $this->string(39)->notNull()->defaultValue('127.0.0.1'),
            'value' => $this->smallInteger(1)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createTable('{{%vote_aggregate}}', [
            'id' => $this->primaryKey(),
            'entity' => $this->integer()->unsigned()->notNull(),
            'target_id' => $this->integer()->notNull(),
            'positive' => $this->integer()->defaultValue(0),
            'negative' => $this->integer()->defaultValue(0),
            'rating' => $this->float()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);
        $this->createIndex('vote_target_idx', '{{%vote}}', ['entity', 'target_id'], false);
        $this->createIndex('vote_user_idx', '{{%vote}}', 'user_id', false);
        $this->createIndex('vote_user_ip_idx', '{{%vote}}', 'user_ip', false);
        $this->createIndex('vote_aggregate_target_idx', '{{%vote_aggregate}}', ['entity', 'target_id'], true);

        $this->createIndex('vote_target_value_idx', '{{%vote}}', ['entity', 'target_id', 'value'], false);
        $this->createIndex('vote_target_user_idx', '{{%vote}}', ['entity', 'target_id', 'user_id'], false);
        $this->alterColumn('{{%vote}}', 'value', $this->boolean()->notNull());
    }

    public function down()
    {
        $this->dropTable('{{%vote}}');
        $this->dropTable('{{%vote_aggregate}}');
    }
}
