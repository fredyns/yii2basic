<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sample_book}}`.
 */
class m190715_024347_create_sample_book_table extends Migration
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

        $this->createTable('{{%sample_book}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->defaultValue(NULL),
            'created_by' => $this->integer()->defaultValue(NULL),
            'updated_at' => $this->integer()->defaultValue(NULL),
            'updated_by' => $this->integer()->defaultValue(NULL),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(NULL),
            'deleted_by' => $this->integer()->defaultValue(NULL),
            'title' => $this->text(),
            'description' => $this->text()->defaultValue(NULL),
            'author_id' => $this->integer()->defaultValue(NULL),
            'editor_id' => $this->integer()->defaultValue(NULL),
            'released_date' => $this->date(),
            ], $tableOptions);

        $this->createIndex('author', '{{%sample_book}}', 'author_id');
        $this->createIndex('editor', '{{%sample_book}}', 'editor_id');

        $this->addForeignKey('fk_sample_book_author', '{{%sample_book}}', 'author_id', '{{%sample_person}}', 'id');
        $this->addForeignKey('fk_sample_book_editor', '{{%sample_book}}', 'editor_id', '{{%sample_person}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_sample_book_editor', '{{%sample_book}}');
        $this->dropForeignKey('fk_sample_book_author', '{{%sample_book}}');

        $this->dropTable('{{%sample_book}}');
    }

}