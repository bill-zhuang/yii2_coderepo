<?php

use yii\db\Migration;

class m170508_081545_add_table_grain_recycle_history extends Migration
{
    public function up()
    {
        $sql = <<<EOF
CREATE TABLE `grain_recycle_history` (
  `grhid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `happen_date` date NOT NULL,
  `count` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'status',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`grhid`),
  UNIQUE KEY `idx_happen_date` (`happen_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170508_081545_add_table_grain_recycle_history cannot be reverted.\n";

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
