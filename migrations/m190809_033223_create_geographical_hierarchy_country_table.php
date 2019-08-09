<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%geographical_hierarchy_country}}`.
 */
class m190809_033223_create_geographical_hierarchy_country_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%geographical_hierarchy_country}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'code' => $this->string(8)->defaultValue(NULL),
        ]);

        $this->createIndex('code', '{{%geographical_hierarchy_country}}', 'code', TRUE);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%geographical_hierarchy_country}}');
    }

}