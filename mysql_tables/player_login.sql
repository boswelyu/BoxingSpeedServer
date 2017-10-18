/*
Navicat MariaDB Data Transfer

Source Server         : Local CentOS7 DB
Source Server Version : 50552
Source Host           : 192.168.74.131:3306
Source Database       : boxingspeed

Target Server Type    : MariaDB
Target Server Version : 50552
File Encoding         : 65001

Date: 2017-10-18 10:50:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for player_login
-- ----------------------------
DROP TABLE IF EXISTS `player_login`;
CREATE TABLE `player_login` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(128) DEFAULT NULL,
  `nickname` varchar(32) DEFAULT NULL,
  `phone_number` varchar(32) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `reg_time` datetime DEFAULT NULL,
  `session_key` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name_index` (`user_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1000003 DEFAULT CHARSET=utf8;
