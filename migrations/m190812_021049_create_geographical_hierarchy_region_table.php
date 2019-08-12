<?php

/**
 * Handles the creation of table `{{%geographical_hierarchy_region}}`.
 */
class m190812_021049_create_geographical_hierarchy_region_table extends app\base\BaseMigration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%geographical_hierarchy_region}}', [
            'id' => $this->primaryKey(10)->unsigned(),
            'name' => $this->string(255),
            'type_id' => $this->integer(10)->unsigned()->defaultValue(NULL),
            'country_id' => $this->integer(10)->unsigned()->defaultValue(NULL),
            'reg_number' => $this->integer(10)->unsigned()->defaultValue(NULL),
        ]);

        $this->createIndex('type', '{{%geographical_hierarchy_region}}', ['type_id']);
        $this->createIndex('country', '{{%geographical_hierarchy_region}}', ['country_id']);
        $this->createIndex('reg_number', '{{%geographical_hierarchy_region}}', ['reg_number']);

        $this->addForeignKey('fk_geohie_region_type', '{{%geographical_hierarchy_region}}', 'type_id', '{{%geographical_hierarchy_type}}', 'id');
        $this->addForeignKey('fk_geohie_region_country', '{{%geographical_hierarchy_region}}', 'country_id', '{{%geographical_hierarchy_country}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_geohie_region_type', '{{%geographical_hierarchy_region}}');
        $this->dropForeignKey('fk_geohie_region_country', '{{%geographical_hierarchy_region}}');

        $this->dropIndex('type', '{{%geographical_hierarchy_region}}');
        $this->dropIndex('country', '{{%geographical_hierarchy_region}}');
        $this->dropIndex('reg_number', '{{%geographical_hierarchy_region}}');

        $this->dropTable('{{%geographical_hierarchy_region}}');
    }

}