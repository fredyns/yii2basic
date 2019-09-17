<?php

/**
 * Handles the creation of table `{{%geographical_hierarchy_district}}`.
 */
class m190813_013157_create_geographical_hierarchy_district_table extends app\base\BaseMigration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%geographical_hierarchy_district}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'type_id' => $this->FKInteger(),
            'city_id' => $this->FKInteger(),
            'reg_number' => $this->FKInteger(),
        ]);

        $this->createIndex('type', '{{%geographical_hierarchy_district}}', ['type_id']);
        $this->createIndex('city', '{{%geographical_hierarchy_district}}', ['city_id']);
        $this->createIndex('reg_number', '{{%geographical_hierarchy_district}}', ['reg_number']);

        $this->addForeignKey('fk_geohie_district_type', '{{%geographical_hierarchy_district}}', 'type_id', '{{%geographical_hierarchy_type}}', 'id');
        $this->addForeignKey('fk_geohie_district_city', '{{%geographical_hierarchy_district}}', 'city_id', '{{%geographical_hierarchy_city}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_geohie_district_type', '{{%geographical_hierarchy_district}}');
        $this->dropForeignKey('fk_geohie_district_city', '{{%geographical_hierarchy_district}}');

        $this->dropIndex('type', '{{%geographical_hierarchy_district}}');
        $this->dropIndex('city', '{{%geographical_hierarchy_district}}');
        $this->dropIndex('reg_number', '{{%geographical_hierarchy_district}}');

        $this->dropTable('{{%geographical_hierarchy_district}}');
    }

}