<?php

use yii\db\Migration;

/**
 * Class m190917_023218_create_geo_address_tables
 */
class m190917_023218_create_geo_address_tables extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
        $table_options = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%geo_address_country}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string(8)->defaultValue(NULL),
        ], $table_options);
        $this->createTable('{{%geo_address_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->text()->defaultValue(NULL),
        ], $table_options);
        $this->createTable('{{%geo_address_region}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type_id' => $this->integer()->defaultValue(NULL),
            'country_id' => $this->integer()->defaultValue(NULL),
            'reg_number' => $this->integer()->defaultValue(NULL),
        ], $table_options);
        $this->createTable('{{%geo_address_city}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type_id' => $this->integer()->defaultValue(NULL),
            'country_id' => $this->integer()->defaultValue(NULL),
            'region_id' => $this->integer()->defaultValue(NULL),
            'reg_number' => $this->integer()->defaultValue(NULL),
        ], $table_options);
        $this->createTable('{{%geo_address_district}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type_id' => $this->integer()->defaultValue(NULL),
            'city_id' => $this->integer()->defaultValue(NULL),
            'reg_number' => $this->integer()->defaultValue(NULL),
        ], $table_options);
        $this->createTable('{{%geo_address_subdistrict}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type_id' => $this->integer()->defaultValue(NULL),
            'district_id' => $this->integer()->defaultValue(NULL),
            'reg_number' => $this->bigInteger()->defaultValue(NULL),
        ], $table_options);

        $this->createIndex('i_geoadr_ctry_code', '{{%geo_address_country}}', 'code', TRUE);

        $this->createIndex('i_geoadr_rgn_type', '{{%geo_address_region}}', ['type_id']);
        $this->createIndex('i_geoadr_rgn_country', '{{%geo_address_region}}', ['country_id']);
        $this->createIndex('i_geoadr_rgn_regnum', '{{%geo_address_region}}', ['reg_number']);

        $this->createIndex('i_geoadr_cty_type', '{{%geo_address_city}}', ['type_id']);
        $this->createIndex('i_geoadr_cty_country', '{{%geo_address_city}}', ['country_id']);
        $this->createIndex('i_geoadr_cty_region', '{{%geo_address_city}}', ['region_id']);
        $this->createIndex('i_geoadr_cty_regnum', '{{%geo_address_city}}', ['reg_number']);

        $this->createIndex('i_geoadr_dstr_type', '{{%geo_address_district}}', ['type_id']);
        $this->createIndex('i_geoadr_dstr_city', '{{%geo_address_district}}', ['city_id']);
        $this->createIndex('i_geoadr_dstr_regnum', '{{%geo_address_district}}', ['reg_number']);

        $this->createIndex('i_geoadr_sdstr_type', '{{%geo_address_subdistrict}}', ['type_id']);
        $this->createIndex('i_geoadr_sdstr_district', '{{%geo_address_subdistrict}}', ['district_id']);
        $this->createIndex('i_geoadr_sdstr_regnum', '{{%geo_address_subdistrict}}', ['reg_number']);

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

        $this->dropIndex('i_geoadr_ctry_code', '{{%geo_address_country}}');

        $this->dropIndex('i_geoadr_rgn_type', '{{%geo_address_region}}');
        $this->dropIndex('i_geoadr_rgn_country', '{{%geo_address_region}}');
        $this->dropIndex('i_geoadr_rgn_regnum', '{{%geo_address_region}}');

        $this->dropIndex('i_geoadr_cty_type', '{{%geo_address_city}}');
        $this->dropIndex('i_geoadr_cty_country', '{{%geo_address_city}}');
        $this->dropIndex('i_geoadr_cty_region', '{{%geo_address_city}}');
        $this->dropIndex('i_geoadr_cty_regnum', '{{%geo_address_city}}');

        $this->dropIndex('i_geoadr_dstr_type', '{{%geo_address_district}}');
        $this->dropIndex('i_geoadr_dstr_city', '{{%geo_address_district}}');
        $this->dropIndex('i_geoadr_dstr_regnum', '{{%geo_address_district}}');

        $this->dropIndex('i_geoadr_sdstr_type', '{{%geo_address_subdistrict}}');
        $this->dropIndex('i_geoadr_sdstr_district', '{{%geo_address_subdistrict}}');
        $this->dropIndex('i_geoadr_sdstr_regnum', '{{%geo_address_subdistrict}}');

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