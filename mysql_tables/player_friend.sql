/*
Navicat MariaDB Data Transfer

Source Server         : Local CentOS7 DB
Source Server Version : 50552
Source Host           : 192.168.74.131:3306
Source Database       : boxingspeed

Target Server Type    : MariaDB
Target Server Version : 50552
File Encoding         : 65001

Date: 2017-10-18 18:52:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for player_friend
-- ----------------------------
DROP TABLE IF EXISTS `player_friend`;
CREATE TABLE `player_friend` (
  `list_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id1` int(11) DEFAULT NULL,
  `user_id2` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`list_idx`),
  KEY `user_id1_idx` (`user_id1`),
  KEY `user_id2_idx` (`user_id2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
