/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50522
Source Host           : localhost:3306
Source Database       : bill

Target Server Type    : MYSQL
Target Server Version : 50522
File Encoding         : 65001

Date: 2015-05-03 16:23:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for backend_user
-- ----------------------------
DROP TABLE IF EXISTS `backend_user`;
CREATE TABLE `backend_user` (
  `bu_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bu_name` varchar(128) NOT NULL DEFAULT '',
  `bu_password_hash` varchar(255) NOT NULL COMMENT 'password hash',
  `bu_auth_key` varchar(255) NOT NULL COMMENT 'remember me auth key',
  `bu_role` int(11) unsigned NOT NULL COMMENT 'user role',
  `bu_status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1:valid, 0: invalid',
  `bu_create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bu_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`bu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
