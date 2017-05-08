<?php

use yii\db\Migration;

class m170508_081415_add_table_backend_acl extends Migration
{
    public function up()
    {
        $sql = <<<EOF
CREATE TABLE `backend_acl` (
  `baid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT 'acl name',
  `module` varchar(100) NOT NULL DEFAULT '' COMMENT 'module name',
  `controller` varchar(100) NOT NULL DEFAULT '' COMMENT 'controller name',
  `action` varchar(100) NOT NULL DEFAULT '' COMMENT 'action name',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'status: 1-valid, 0-invalid',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`baid`),
  UNIQUE KEY `idx_m_c_a` (`module`,`controller`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170508_081415_add_table_backend_acl cannot be reverted.\n";

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
