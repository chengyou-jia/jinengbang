/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : jinengbang

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-05-09 12:53:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for apply
-- ----------------------------
DROP TABLE IF EXISTS `apply`;
CREATE TABLE `apply` (
  `apply_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `help_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `status` enum('1','2','0') NOT NULL DEFAULT '0' COMMENT '0表示待确认，1为成功,2为失败',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '得分,不通过为0分',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`apply_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

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
  `is_free` enum('1','3','2','0') NOT NULL DEFAULT '0' COMMENT '0代表有酬，1代表无酬，2代表工时，3代表其他',
  `askfor_type` enum('4','3','2','1','0') NOT NULL COMMENT '0为其他,1为学霸大神，2为艺术天才，3为技术大佬，4为生活雷锋',
  `is_complaint` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1表示被投诉',
  `type` enum('1','0') NOT NULL DEFAULT '0' COMMENT '0为需要个人，1为需要多人',
  `title` varchar(255) NOT NULL,
  `has_finished` enum('1','0') NOT NULL DEFAULT '0' COMMENT '0表示未完成',
  `publisher` enum('2','1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`help_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for help_comment
-- ----------------------------
DROP TABLE IF EXISTS `help_comment`;
CREATE TABLE `help_comment` (
  `help_comment_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `prior` int(11) NOT NULL DEFAULT '-1',
  `help_id` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`help_comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for label
-- ----------------------------
DROP TABLE IF EXISTS `label`;
CREATE TABLE `label` (
  `label_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('0','1','2','3','4') NOT NULL DEFAULT '0' COMMENT '具体表示暂定',
  `score` int(11) NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`label_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for message
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `is_office` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1为官方消息',
  `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '0表示未读，1表示已读',
  `type` enum('5','4','3','2','1','0') NOT NULL COMMENT '0官方系统消息 1求助id（被评论，或有人报名）2求助评论id3提问id4提问评论id5求助完成，获得分数',
  `type_id` int(11) NOT NULL COMMENT '对应类型要跳转的id',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for question
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('1','2','3','0') NOT NULL DEFAULT '0' COMMENT '0提问 1吐槽 2表白 3其他',
  `content` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `like` int(11) NOT NULL DEFAULT '0',
  `browse` int(11) NOT NULL DEFAULT '0',
  `reply` int(11) NOT NULL DEFAULT '0',
  `is_complaint` enum('1','0') NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `is_anonymous` enum('1','0') NOT NULL DEFAULT '0' COMMENT '是否匿名 0非1匿',
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for question_comment
-- ----------------------------
DROP TABLE IF EXISTS `question_comment`;
CREATE TABLE `question_comment` (
  `question_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `question_id` int(11) NOT NULL,
  `prior` int(11) NOT NULL DEFAULT '-1',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`question_comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for suggestion
-- ----------------------------
DROP TABLE IF EXISTS `suggestion`;
CREATE TABLE `suggestion` (
  `suggestion_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `reply` varchar(255) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '0代表为未被回复',
  PRIMARY KEY (`suggestion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

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
  `wechat_id` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL COMMENT '存放头像地址',
  `update_time` datetime NOT NULL,
  `create_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `role` enum('2','1','0') NOT NULL DEFAULT '0' COMMENT '0为普通用户 1为管理员 2为开发者兼管理员',
  `new_message` enum('1','0') NOT NULL DEFAULT '0',
  `gender` enum('1','0') NOT NULL COMMENT '0表示男性1表示女性',
  `major` varchar(255) NOT NULL,
  `grade` varchar(255) NOT NULL,
  `qq` int(11) NOT NULL,
  `intro` varchar(255) DEFAULT NULL COMMENT '个人介绍',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '总积分',
  `help_num` int(11) NOT NULL DEFAULT '0' COMMENT '总帮助次数',
  `cert_photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
