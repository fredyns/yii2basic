<?php

namespace app\base;

use yii\db\Migration;

/**
 * Description of BaseMigration
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class BaseMigration extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function createTable($table, $columns, $options = null)
    {
        if ($this->db->driverName === 'mysql' && $options === null) {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        return parent::createTable($table, $columns, $options);
    }

}