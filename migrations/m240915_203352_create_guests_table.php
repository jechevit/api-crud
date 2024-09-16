<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%guests}}`.
 */
class m240915_203352_create_guests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%guests}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'email' => $this->string()->unique(),
            'phone' => $this->string()->notNull()->unique(),
            'country_code' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%guests}}');
    }
}
