<?php

use yii\db\Migration;

class m170508_081354_add_table_backend_role extends Migration
{
    public function up()
    {
        $sql = <<<EOF
CREATE TABLE `backend_role` (
  `brid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(100) NOT NULL DEFAULT '' COMMENT 'backend role name',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'status: 1-valid, 0-invalid',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`brid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170508_081354_add_table_backend_role cannot be reverted.\n";

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
