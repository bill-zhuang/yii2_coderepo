<?php

use yii\db\Migration;

class m170508_081430_add_table_backend_role_acl extends Migration
{
    public function up()
    {
        $sql = <<<EOF
CREATE TABLE `backend_role_acl` (
  `braid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brid` int(10) unsigned NOT NULL COMMENT 'backend role pkid',
  `baid` int(10) unsigned NOT NULL COMMENT 'backend_acl pkid',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'status: 1-valid, 0-invalid',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`braid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170508_081430_add_table_backend_role_acl cannot be reverted.\n";

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
