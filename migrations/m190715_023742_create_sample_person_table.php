<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sample_person}}`.
 */
class m190715_023742_create_sample_person_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%sample_person}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->defaultValue(NULL),
            'created_by' => $this->integer()->defaultValue(NULL),
            'updated_at' => $this->integer()->defaultValue(NULL),
            'updated_by' => $this->integer()->defaultValue(NULL),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(NULL),
            'deleted_by' => $this->integer()->defaultValue(NULL),
            'name' => $this->string(255),
            ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sample_person}}');
    }

}