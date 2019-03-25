/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : shijian2019

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-03-25 10:21:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for guide
-- ----------------------------
DROP TABLE IF EXISTS `guide`;
CREATE TABLE `guide` (
  `guide_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`guide_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for note
-- ----------------------------
DROP TABLE IF EXISTS `note`;
CREATE TABLE `note` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `reply_id` int(11) DEFAULT NULL,
  `reply` varchar(255) DEFAULT NULL,
  `reply_time` datetime DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0未回复1已回复',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for notification
-- ----------------------------
DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `type` enum('2','4','3','1') NOT NULL COMMENT '（1成功，2警告，3消息，4错误）',
  `overtime` datetime NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for profile
-- ----------------------------
DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile` (
  `profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `gender` enum('1','0') NOT NULL COMMENT '0表示女性、1表示男性',
  `nation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:汉族\r\n2:蒙古族\r\n3:回族\r\n4:藏族\r\n5:维吾尔族\r\n6:苗族\r\n7:彝族\r\n8:壮族\r\n9:布依族\r\n10:朝鲜族\r\n11:满族\r\n12:侗族\r\n13:瑶族\r\n14:白族\r\n15:土家族\r\n16:哈尼族\r\n17:哈萨克族\r\n18:傣族\r\n19:黎族\r\n20:傈僳族\r\n21:佤族\r\n22:畲族\r\n23:高山族\r\n24:拉祜族\r\n25:水族\r\n26:东乡族\r\n27:纳西族\r\n28:景颇族\r\n29:柯尔克孜族\r\n30:土族\r\n31:达斡尔族\r\n32:仫佬族\r\n33:羌族\r\n34:布朗族\r\n35:撒拉族\r\n36:毛南族\r\n37:仡佬族\r\n38:锡伯族\r\n39:阿昌族\r\n40:普米族\r\n41:塔吉克族\r\n42:怒族\r\n43:乌孜别克族\r\n44:俄罗斯族\r\n45:鄂温克族\r\n46:德昂族\r\n47:保安族\r\n48:裕固族\r\n49:京族\r\n50:塔塔尔族\r\n51:独龙族\r\n52:鄂伦春族\r\n53:赫哲族\r\n54:门巴族\r\n55:珞巴族\r\n56:基诺族\r\n57:外国血统\r\n58:其他',
  `card_type` enum('0') NOT NULL DEFAULT '0' COMMENT '1表示身份证，0表示其他',
  `card_id` bigint(50) NOT NULL,
  `outlook` varchar(255) NOT NULL,
  `native_area` varchar(255) NOT NULL,
  `grade` varchar(255) NOT NULL,
  `institute` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: 其他\n1：钱学森学院\n2：材料学院\n3：数统学院\n4：电信学院\n5：生命学院\n6：能动学院\n7：外语学院\n8：理学院\n9：经金学院\n10：医学部\n11：电气学院\n12：人居学院\n13：人文学院\n14：化工学院\n15：机械学院\n16：软件学院\n17：航天学院\n18：公管学院\n19：马克思学院\n20：法学院',
  `edu_level` enum('1','0') NOT NULL DEFAULT '0' COMMENT '0表示本科生1表示研究生2表示其他',
  `college` tinyint(4) NOT NULL DEFAULT '9' COMMENT '0：文治书院\n1：南洋书院\n2：宗濂书院\n3：彭康书院\n4：仲英书院\n5：启德书院\n6：励志书院\n7：崇实书院\n8：钱学森书院\n9：其他',
  `user_number` bigint(20) NOT NULL,
  `major` varchar(255) NOT NULL,
  `clazz` varchar(255) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for recruit
-- ----------------------------
DROP TABLE IF EXISTS `recruit`;
CREATE TABLE `recruit` (
  `recruit_id` int(11) NOT NULL AUTO_INCREMENT,
  `recruit_type` enum('1','0') NOT NULL DEFAULT '0',
  `status` enum('1','0') NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`recruit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for team
-- ----------------------------
DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_name` varchar(255) NOT NULL,
  `plan_begin_time` datetime NOT NULL,
  `plan_end_time` datetime NOT NULL,
  `real_begin_time` datetime DEFAULT NULL,
  `real_end_time` datetime DEFAULT NULL,
  `place` varchar(255) NOT NULL,
  `project_type` tinyint(11) NOT NULL COMMENT '类型待更新',
  `event_background` varchar(255) NOT NULL,
  `event_aim` varchar(255) NOT NULL,
  `event_content` varchar(255) NOT NULL,
  `fee_transport` int(11) NOT NULL,
  `fee_print` int(11) NOT NULL,
  `fee_other` int(11) NOT NULL,
  `fee_sum` int(11) NOT NULL,
  `teacher_follow` enum('0','1') NOT NULL COMMENT '0表示不跟随，1表示跟随',
  `is_declared` enum('0','1') NOT NULL COMMENT '0表示不申报，1表示申报',
  `declare_type` tinyint(11) NOT NULL COMMENT '类型待更新',
  `leader_id` int(11) NOT NULL,
  `vice_leader_id` int(11) NOT NULL,
  `teacher_name` varchar(10) NOT NULL,
  `belong` tinyint(4) NOT NULL COMMENT '0: 没有单位，是学生 1：校团委 2：文治书院 3：南洋书院 4：宗濂书院 5：彭康书院 6：仲英书院 7：启德书院 8：励志书院 9：崇实书院 10：钱学森书院 11：钱学森学院 12：材料学院 13：数统学院 14：电信学院 15：生命学院 16：能动学院 17：外语学院 18：理学院 19：经金学院 20：医学部 21：电气学院 22：人居学院 23：人文学院 24：化工学院 25：机械学院 26：软件学院 27：航天学院 28：公管学院 29：马克思学院 30：法学院 31：苏州研究生院 32：校友关系发展部',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：立项申请保存还未提交\r\n1：已经立项，待挂靠单位审核\r\n2：挂靠单位审核不通过\r\n3：挂靠单位审核通过，未出行\r\n4：正在出行\r\n5：已返回，待团委测验\r\n6：团委测验不通过\r\n7：团委测验通过',
  `code` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `suggestion` varchar(255) DEFAULT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for team_link
-- ----------------------------
DROP TABLE IF EXISTS `team_link`;
CREATE TABLE `team_link` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `net_id` varchar(30) NOT NULL,
  `role` enum('3','2','1','0') NOT NULL DEFAULT '0' COMMENT '权限（）',
  `org` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: 没有单位，是学生\n1：校团委\n2：文治书院\n3：南洋书院\n4：宗濂书院\n5：彭康书院\n6：仲英书院\n7：启德书院\n8：励志书院\n9：崇实书院\n10：钱学森书院\n11：钱学森学院\n12：材料学院\n13：数统学院\n14：电信学院\n15：生命学院\n16：能动学院\n17：外语学院\n18：理学院\n19：经金学院\n20：医学部\n21：电气学院\n22：人居学院\n23：人文学院\n24：化工学院\n25：机械学院\n26：软件学院\n27：航天学院\n28：公管学院\n29：马克思学院\n30：法学院\n31：苏州研究生院\n32：校友关系发展部',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
