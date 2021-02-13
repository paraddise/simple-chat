<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m210130_083208_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->unique()->notNull(),
            'email' => $this->string()->unique()->notNull(),
            'verification_token' => $this->string()->unique(),
            'password' => $this->string(),
            'password_reset_token' => $this->string(),
            'access_token' => $this->string()->unique(),
            'auth_key' => $this->string()->unique()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
