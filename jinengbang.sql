/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : jinengbang

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-04-06 11:12:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for help
-- ----------------------------
DROP TABLE IF EXISTS `help`;
CREATE TABLE `help` (
  `help_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `browse` int(11) NOT NULL DEFAULT '0',
  `like` int(11) NOT NULL DEFAULT '0',
  `reply` int(11) NOT NULL DEFAULT '0',
  `apply_num` int(11) NOT NULL DEFAULT '0' COMMENT '已报名人数',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `is_free` enum('1','0') NOT NULL DEFAULT '0' COMMENT '0代表有酬，1代表无酬',
  `askfor_type` enum('4','3','2','1','0') NOT NULL,
  PRIMARY KEY (`help_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for help_comment
-- ----------------------------
DROP TABLE IF EXISTS `help_comment`;
CREATE TABLE `help_comment` (
  `help_comment_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `prior` int(11) NOT NULL DEFAULT '-1',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `help_id` int(11) NOT NULL,
  PRIMARY KEY (`help_comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for label
-- ----------------------------
DROP TABLE IF EXISTS `label`;
CREATE TABLE `label` (
  `label_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('0','5','4','3','2','1') NOT NULL DEFAULT '0' COMMENT '具体表示暂定',
  `score` int(11) NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`label_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for question
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('1','0') NOT NULL,
  `content` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `like` int(11) NOT NULL DEFAULT '0',
  `browse` int(11) NOT NULL DEFAULT '0',
  `reply` int(11) NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for question_comment
-- ----------------------------
DROP TABLE IF EXISTS `question_comment`;
CREATE TABLE `question_comment` (
  `question_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `prior` int(11) NOT NULL DEFAULT '-1',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`question_comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `wechat` varchar(255) NOT NULL COMMENT '此处手机号微信号都可以,能查找到即可',
  `mobile` bigint(11) NOT NULL,
  `is_cert` enum('1','2','0') NOT NULL DEFAULT '0' COMMENT '0表示未认证，1表示正在审核认证，2表示已认证(此处的认证为检验是否为校内成员）',
  `is_official` enum('0','1') DEFAULT '0' COMMENT '0表示未认证1表示已经认证为官方号',
  `nickname` varchar(255) NOT NULL,
  `wechat_id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL COMMENT '存放头像地址',
  `update_time` datetime NOT NULL,
  `create_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `cert_photo` varchar(255) DEFAULT NULL,
  `role` enum('2','1','0') NOT NULL DEFAULT '0' COMMENT '0为普通用户 1为管理员 2为开发者兼管理员',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
