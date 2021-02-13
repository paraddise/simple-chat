<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chat}}`.
 */
class m210205_192511_create_chat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chat}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('chat_created_by_fk', '{{%chat}}', 'created_by', '{{%user}}', 'id');
        $this->createIndex('chat_created_at_idx', '{{%chat}}', 'created_at');
        $this->createIndex('chat_updated_at_idx', '{{%chat}}', 'updated_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('chat_created_at_idx', '{{%chat}}');
        $this->dropIndex('chat_updated_at_idx', '{{%chat}}');
        $this->dropForeignKey('chat_created_by_fk', '{{%chat}}');
        $this->dropTable('{{%chat}}');
    }
}
