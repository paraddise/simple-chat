<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chat_message}}`.
 */
class m210205_193634_create_chat_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer()->notNull(),
            'text' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('message_chat_id_fk', '{{%message}}', 'chat_id', '{{%chat}}', 'id', 'CASCADE');
        $this->addForeignKey('message_created_by_fk', '{{%message}}', 'created_by', '{{%user}}', 'id', 'CASCADE');
        $this->createIndex('message_created_at_idx', '{{%message}}', 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('message_chat_id_fk', '{{%message}}');
        $this->dropForeignKey('message_created_by_fk', '{{%message}}');
        $this->dropIndex('message_created_at_idx', '{{%message}}');
        $this->dropTable('{{%message}}');
    }
}
