<?php

use yii\db\Migration;

class m170508_081200_add_table_backend_user extends Migration
{
    public function up()
    {
        $sql = <<<EOF
CREATE TABLE `backend_user` (
  `buid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `salt` char(64) NOT NULL COMMENT 'password salt',
  `brid` int(10) unsigned NOT NULL COMMENT 'backend role pkid',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1:valid, 0: invalid',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`buid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170508_081200_add_table_backend_user cannot be reverted.\n";

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
