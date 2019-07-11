<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%yii_session}}`.
 */
class m190614_025946_create_yii_session_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName !== 'mysql') {
            echo "m190623_025946_create_yii_session_table migration only support mysql.\n";
            return TRUE;
        }
        
        $sql = <<<SQL
            CREATE TABLE `yii_session` (
                `id` CHAR(64) NOT NULL COLLATE 'utf8_unicode_ci',
                `expire` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data` LONGBLOB NULL,
                PRIMARY KEY (`id`)
            )
            COLLATE='utf8_unicode_ci'
            ENGINE=InnoDB
            ;
SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->driverName !== 'mysql') {
            echo "m190614_025946_create_yii_session_table migration only support mysql.\n";
            return TRUE;
        }

        $this->dropTable('yii_session');
    }

}