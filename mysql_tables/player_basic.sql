/*
Navicat MariaDB Data Transfer

Source Server         : Local CentOS7 DB
Source Server Version : 50552
Source Host           : 192.168.74.131:3306
Source Database       : boxingspeed

Target Server Type    : MariaDB
Target Server Version : 50552
File Encoding         : 65001

Date: 2017-10-18 10:50:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for player_basic
-- ----------------------------
DROP TABLE IF EXISTS `player_basic`;
CREATE TABLE `player_basic` (
  `user_id` int(11) NOT NULL,
  `gender` smallint(6) DEFAULT NULL,
  `age` smallint(6) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `avatar_url` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
