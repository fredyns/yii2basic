<?php

/**
 * Class m190917_021934_create_sample_tables
 */
class m190917_021934_create_sample_tables extends \app\base\BaseMigration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
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
        ]);

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
            'author_id' => $this->FKInteger(),
            'editor_id' => $this->FKInteger(),
            'released_date' => $this->date()->defaultValue(NULL),
        ]);

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