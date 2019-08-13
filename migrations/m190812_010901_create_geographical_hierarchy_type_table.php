<?php

/**
 * Handles the creation of table `{{%geographical_hierarchy_type}}`.
 */
class m190812_010901_create_geographical_hierarchy_type_table extends app\base\BaseMigration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%geographical_hierarchy_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'description' => $this->text()->defaultValue(NULL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%geographical_hierarchy_type}}');
    }

}