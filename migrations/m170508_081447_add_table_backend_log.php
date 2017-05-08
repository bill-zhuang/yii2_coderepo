<?php

use yii\db\Migration;

class m170508_081447_add_table_backend_log extends Migration
{
    public function up()
    {
        $sql = <<<EOF
CREATE TABLE `backend_log` (
  `blid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT 'type(insert, update, delete)',
  `table` varchar(255) NOT NULL DEFAULT '' COMMENT 'table name',
  `content` text NOT NULL COMMENT 'SQL',
  `buid` int(11) NOT NULL COMMENT 'backend_user primary key',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 : valid 0 : invalid',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`blid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170508_081447_add_table_backend_log cannot be reverted.\n";

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
