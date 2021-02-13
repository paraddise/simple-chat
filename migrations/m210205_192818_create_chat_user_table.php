<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chat_user}}`.
 */
class m210205_192818_create_chat_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chat_user}}', [
            'chat_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'role' => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey('chat_user_user_id_fk', '{{%chat_user}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('chat_user_chat_id_fk', '{{%chat_user}}', 'chat_id', '{{%chat}}', 'id', 'CASCADE');
        $this->createIndex('chat_user_created_at_idx', '{{%chat_user}}', 'created_at');
        $this->addPrimaryKey('chat_user_pk', '{{%chat_user}}', ['chat_id', 'user_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('chat_user_created_at_idx', '{{%chat_user}}');
        $this->dropForeignKey('chat_user_user_id_fk', '{{%chat_user}}');
        $this->dropForeignKey('chat_user_chat_id_fk', '{{%chat_user}}');
        $this->dropPrimaryKey('chat_user_pk', '{{%chat_user}}');
        $this->dropTable('{{%chat_user}}');
    }
}
