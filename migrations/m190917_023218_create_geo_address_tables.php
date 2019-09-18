<?php

/**
 * Class m190917_023218_create_geo_address_tables
 */
class m190917_023218_create_geo_address_tables extends \app\base\BaseMigration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%geo_address_country}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string(8)->defaultValue(NULL),
        ]);
        $this->createTable('{{%geo_address_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->text()->defaultValue(NULL),
        ]);
        $this->createTable('{{%geo_address_region}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type_id' => $this->FKInteger(),
            'country_id' => $this->FKInteger(),
            'reg_number' => $this->FKInteger(),
        ]);
        $this->createTable('{{%geo_address_city}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type_id' => $this->FKInteger(),
            'country_id' => $this->FKInteger(),
            'region_id' => $this->FKInteger(),
            'reg_number' => $this->FKInteger(),
        ]);
        $this->createTable('{{%geo_address_district}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type_id' => $this->FKInteger(),
            'city_id' => $this->FKInteger(),
            'reg_number' => $this->FKInteger(),
        ]);
        $this->createTable('{{%geo_address_subdistrict}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type_id' => $this->FKInteger(),
            'district_id' => $this->FKInteger(),
            'reg_number' => $this->FKBigInteger(),
        ]);

        $this->createIndex('code', '{{%geo_address_country}}', 'code', TRUE);

        $this->createIndex('type', '{{%geo_address_region}}', ['type_id']);
        $this->createIndex('country', '{{%geo_address_region}}', ['country_id']);
        $this->createIndex('reg_number', '{{%geo_address_region}}', ['reg_number']);

        $this->createIndex('type', '{{%geo_address_city}}', ['type_id']);
        $this->createIndex('country', '{{%geo_address_city}}', ['country_id']);
        $this->createIndex('region', '{{%geo_address_city}}', ['region_id']);
        $this->createIndex('reg_number', '{{%geo_address_city}}', ['reg_number']);

        $this->createIndex('type', '{{%geo_address_district}}', ['type_id']);
        $this->createIndex('city', '{{%geo_address_district}}', ['city_id']);
        $this->createIndex('reg_number', '{{%geo_address_district}}', ['reg_number']);

        $this->createIndex('type', '{{%geo_address_subdistrict}}', ['type_id']);
        $this->createIndex('district', '{{%geo_address_subdistrict}}', ['district_id']);
        $this->createIndex('reg_number', '{{%geo_address_subdistrict}}', ['reg_number']);

        $this->addForeignKey('fk_geoadr_region_type', '{{%geo_address_region}}', 'type_id', '{{%geo_address_type}}', 'id');
        $this->addForeignKey('fk_geoadr_region_country', '{{%geo_address_region}}', 'country_id', '{{%geo_address_country}}', 'id');

        $this->addForeignKey('fk_geoadr_city_type', '{{%geo_address_city}}', 'type_id', '{{%geo_address_type}}', 'id');
        $this->addForeignKey('fk_geoadr_city_country', '{{%geo_address_city}}', 'country_id', '{{%geo_address_country}}', 'id');
        $this->addForeignKey('fk_geoadr_city_region', '{{%geo_address_city}}', 'region_id', '{{%geo_address_region}}', 'id');

        $this->addForeignKey('fk_geoadr_district_type', '{{%geo_address_district}}', 'type_id', '{{%geo_address_type}}', 'id');
        $this->addForeignKey('fk_geoadr_district_city', '{{%geo_address_district}}', 'city_id', '{{%geo_address_city}}', 'id');

        $this->addForeignKey('fk_geoadr_subdist_type', '{{%geo_address_subdistrict}}', 'type_id', '{{%geo_address_type}}', 'id');
        $this->addForeignKey('fk_geoadr_subdist_city', '{{%geo_address_subdistrict}}', 'district_id', '{{%geo_address_district}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_geoadr_subdist_type', '{{%geo_address_subdistrict}}');
        $this->dropForeignKey('fk_geoadr_subdist_city', '{{%geo_address_subdistrict}}');

        $this->dropForeignKey('fk_geoadr_district_type', '{{%geo_address_district}}');
        $this->dropForeignKey('fk_geoadr_district_city', '{{%geo_address_district}}');

        $this->dropForeignKey('fk_geoadr_city_type', '{{%geo_address_city}}');
        $this->dropForeignKey('fk_geoadr_city_country', '{{%geo_address_city}}');
        $this->dropForeignKey('fk_geoadr_city_region', '{{%geo_address_city}}');

        $this->dropForeignKey('fk_geoadr_region_type', '{{%geo_address_region}}');
        $this->dropForeignKey('fk_geoadr_region_country', '{{%geo_address_region}}');

        $this->dropIndex('type', '{{%geo_address_subdistrict}}');
        $this->dropIndex('district', '{{%geo_address_subdistrict}}');
        $this->dropIndex('reg_number', '{{%geo_address_subdistrict}}');

        $this->dropIndex('type', '{{%geo_address_district}}');
        $this->dropIndex('city', '{{%geo_address_district}}');
        $this->dropIndex('reg_number', '{{%geo_address_district}}');

        $this->dropIndex('type', '{{%geo_address_city}}');
        $this->dropIndex('country', '{{%geo_address_city}}');
        $this->dropIndex('region', '{{%geo_address_city}}');
        $this->dropIndex('reg_number', '{{%geo_address_city}}');

        $this->dropIndex('type', '{{%geo_address_region}}');
        $this->dropIndex('country', '{{%geo_address_region}}');
        $this->dropIndex('reg_number', '{{%geo_address_region}}');

        $this->dropTable('{{%geo_address_subdistrict}}');
        $this->dropTable('{{%geo_address_district}}');
        $this->dropTable('{{%geo_address_city}}');
        $this->dropTable('{{%geo_address_region}}');
        $this->dropTable('{{%geo_address_type}}');
        $this->dropTable('{{%geo_address_country}}');
    }
    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190917_023218_create_geo_address_tables cannot be reverted.\n";

      return false;
      }
     */

}