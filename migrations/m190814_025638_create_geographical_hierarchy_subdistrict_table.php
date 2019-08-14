<?php

/**
 * Handles the creation of table `{{%geographical_hierarchy_subdistrict}}`.
 */
class m190814_025638_create_geographical_hierarchy_subdistrict_table extends \app\base\BaseMigration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%geographical_hierarchy_subdistrict}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'type_id' => $this->FKInteger(),
            'district_id' => $this->FKInteger(),
            'reg_number' => $this->FKBigInteger(),
        ]);

        $this->createIndex('type', '{{%geographical_hierarchy_subdistrict}}', ['type_id']);
        $this->createIndex('district', '{{%geographical_hierarchy_subdistrict}}', ['district_id']);
        $this->createIndex('reg_number', '{{%geographical_hierarchy_subdistrict}}', ['reg_number']);

        $this->addForeignKey('fk_geohie_subdistrict_type', '{{%geographical_hierarchy_subdistrict}}', 'type_id', '{{%geographical_hierarchy_type}}', 'id');
        $this->addForeignKey('fk_geohie_subdistrict_city', '{{%geographical_hierarchy_subdistrict}}', 'district_id', '{{%geographical_hierarchy_district}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_geohie_subdistrict_type', '{{%geographical_hierarchy_subdistrict}}');
        $this->dropForeignKey('fk_geohie_subdistrict_city', '{{%geographical_hierarchy_subdistrict}}');

        $this->dropIndex('type', '{{%geographical_hierarchy_subdistrict}}');
        $this->dropIndex('district', '{{%geographical_hierarchy_subdistrict}}');
        $this->dropIndex('reg_number', '{{%geographical_hierarchy_subdistrict}}');

        $this->dropTable('{{%geographical_hierarchy_subdistrict}}');
    }

}