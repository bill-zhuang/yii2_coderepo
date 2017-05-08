<?php

use yii\db\Migration;

class m170508_081525_add_table_finance_payment extends Migration
{
    public function up()
    {
        $sql = <<<EOF
CREATE TABLE `finance_payment` (
  `fpid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment` float(9,2) unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `detail` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170508_081525_add_table_finance_payment cannot be reverted.\n";

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
