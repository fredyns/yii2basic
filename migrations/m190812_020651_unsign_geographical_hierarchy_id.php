<?php

use yii\db\Migration;

/**
 * Class m190812_020651_unsign_geographical_hierarchy_id
 */
class m190812_020651_unsign_geographical_hierarchy_id extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%geographical_hierarchy_country}}', 'id', $this->integer(10)->unsigned());
        $this->alterColumn('{{%geographical_hierarchy_type}}', 'id', $this->integer(10)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%geographical_hierarchy_country}}', 'id', $this->integer());
        $this->alterColumn('{{%geographical_hierarchy_type}}', 'id', $this->integer());
    }
    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190812_020651_unsign_geographical_hierarchy_id cannot be reverted.\n";

      return false;
      }
     */

}