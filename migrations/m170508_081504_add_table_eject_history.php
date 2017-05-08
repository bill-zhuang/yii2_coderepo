<?php

use yii\db\Migration;

class m170508_081504_add_table_eject_history extends Migration
{
    public function up()
    {
        $sql = <<<EOF
CREATE TABLE `eject_history` (
  `ehid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `happen_date` date NOT NULL,
  `count` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1-dream, 2-bad',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'status',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ehid`),
  UNIQUE KEY `idx_happend_date_type` (`happen_date`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170508_081504_add_table_eject_history cannot be reverted.\n";

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
