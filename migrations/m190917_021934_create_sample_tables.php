<?php

use yii\db\Migration;

/**
 * Class m190917_021934_create_sample_tables
 */
class m190917_021934_create_sample_tables extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
        $table_options = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%sample_person}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->defaultValue(NULL),
            'created_by' => $this->integer()->defaultValue(NULL),
            'updated_at' => $this->integer()->defaultValue(NULL),
            'updated_by' => $this->integer()->defaultValue(NULL),
            'is_deleted' => $this->tinyInteger()->null()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(NULL),
            'deleted_by' => $this->integer()->defaultValue(NULL),
            'name' => $this->string(),
        ], $table_options);

        $this->createTable('{{%sample_book}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->defaultValue(NULL),
            'created_by' => $this->integer()->defaultValue(NULL),
            'updated_at' => $this->integer()->defaultValue(NULL),
            'updated_by' => $this->integer()->defaultValue(NULL),
            'is_deleted' => $this->tinyInteger()->null()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(NULL),
            'deleted_by' => $this->integer()->defaultValue(NULL),
            'title' => $this->text(),
            'description' => $this->text()->defaultValue(NULL),
            'author_id' => $this->integer()->defaultValue(NULL),
            'editor_id' => $this->integer()->defaultValue(NULL),
            'released_date' => $this->date()->defaultValue(NULL),
        ], $table_options);

        $this->createIndex('i_sample_book_author', '{{%sample_book}}', 'author_id');
        $this->createIndex('i_sample_book_editor', '{{%sample_book}}', 'editor_id');

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

        $this->dropIndex('i_sample_book_author', '{{%sample_book}}');
        $this->dropIndex('i_sample_book_editor', '{{%sample_book}}');

        $this->dropTable('{{%sample_book}}');
        $this->dropTable('{{%sample_person}}');
    }
    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190917_021934_create_sample_tables cannot be reverted.\n";

      return false;
      }
     */

}