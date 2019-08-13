<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%geographical_hierarchy_city}}`.
 */
class m190812_143832_create_geographical_hierarchy_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%geographical_hierarchy_city}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'type_id' => $this->integer(10)->unsigned()->defaultValue(NULL),
            'country_id' => $this->integer(10)->unsigned()->defaultValue(NULL),
            'region_id' => $this->integer(10)->unsigned()->defaultValue(NULL),
            'reg_number' => $this->integer(10)->unsigned()->defaultValue(NULL),
        ]);

        $this->createIndex('type', '{{%geographical_hierarchy_city}}', ['type_id']);
        $this->createIndex('country', '{{%geographical_hierarchy_city}}', ['country_id']);
        $this->createIndex('region', '{{%geographical_hierarchy_city}}', ['region_id']);
        $this->createIndex('reg_number', '{{%geographical_hierarchy_city}}', ['reg_number']);

        $this->addForeignKey('fk_geohie_city_type', '{{%geographical_hierarchy_city}}', 'type_id', '{{%geographical_hierarchy_type}}', 'id');
        $this->addForeignKey('fk_geohie_city_country', '{{%geographical_hierarchy_city}}', 'country_id', '{{%geographical_hierarchy_country}}', 'id');
        $this->addForeignKey('fk_geohie_city_region', '{{%geographical_hierarchy_city}}', 'region_id', '{{%geographical_hierarchy_region}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_geohie_city_type', '{{%geographical_hierarchy_city}}');
        $this->dropForeignKey('fk_geohie_city_country', '{{%geographical_hierarchy_city}}');
        $this->dropForeignKey('fk_geohie_city_region', '{{%geographical_hierarchy_city}}');

        $this->dropIndex('type', '{{%geographical_hierarchy_city}}');
        $this->dropIndex('country', '{{%geographical_hierarchy_city}}');
        $this->dropIndex('region', '{{%geographical_hierarchy_city}}');
        $this->dropIndex('reg_number', '{{%geographical_hierarchy_city}}');

        $this->dropTable('{{%geographical_hierarchy_city}}');
    }
}
