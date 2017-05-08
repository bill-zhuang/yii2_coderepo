<?php

use yii\db\Migration;

class m170508_081535_add_table_finance_payment_map extends Migration
{
    public function up()
    {
        $sql = <<<EOF
CREATE TABLE `finance_payment_map` (
  `fpmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fpid` int(10) unsigned NOT NULL COMMENT 'finance payment primary key',
  `fcid` int(10) unsigned NOT NULL COMMENT 'finance category primary key',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fpmid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170508_081535_add_table_finance_payment_map cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
