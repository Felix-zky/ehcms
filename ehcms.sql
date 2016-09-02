/*
MySQL Backup
Source Server Version: 5.6.17
Source Database: ehcms
Date: 2016/9/2 18:08:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `eh_admin_desktop`
-- ----------------------------
DROP TABLE IF EXISTS `eh_admin_desktop`;
CREATE TABLE `eh_admin_desktop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '桌面图标名称',
  `icon` varchar(100) NOT NULL COMMENT '图标图片名称',
  `top_menu_ids` varchar(100) NOT NULL COMMENT '指向的URL',
  `sort` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `eh_admin_menu`
-- ----------------------------
DROP TABLE IF EXISTS `eh_admin_menu`;
CREATE TABLE `eh_admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT '菜单父ID',
  `name` varchar(50) NOT NULL COMMENT '菜单名称',
  `icon` varchar(100) DEFAULT NULL COMMENT '菜单图标',
  `url` varchar(200) DEFAULT NULL COMMENT '对应页面的URL',
  `sort` tinyint(4) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `eh_article`
-- ----------------------------
DROP TABLE IF EXISTS `eh_article`;
CREATE TABLE `eh_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL COMMENT '文章标题',
  `keywords` varchar(200) DEFAULT NULL COMMENT '文章关键词',
  `description` varchar(500) DEFAULT '' COMMENT '文章描述',
  `markdown` text COMMENT 'markdown源码',
  `content` text NOT NULL COMMENT '解析后的内容',
  `add_time` bigint(20) NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `eh_resource`
-- ----------------------------
DROP TABLE IF EXISTS `eh_resource`;
CREATE TABLE `eh_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '资源文件名称（不带后缀）',
  `extension` varchar(50) NOT NULL COMMENT '资源文件后缀',
  `type` tinyint(4) NOT NULL COMMENT '资源类型',
  `path` varchar(200) NOT NULL COMMENT '不带域名的绝对地址路径',
  `add_time` bigint(20) NOT NULL COMMENT '进入资源库的时间（在中转站的时间不算）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `eh_resource_relation`
-- ----------------------------
DROP TABLE IF EXISTS `eh_resource_relation`;
CREATE TABLE `eh_resource_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `relation_type` tinyint(4) NOT NULL COMMENT '关联类型',
  `relation_id` int(11) NOT NULL COMMENT '关联ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `eh_resource_transfer`
-- ----------------------------
DROP TABLE IF EXISTS `eh_resource_transfer`;
CREATE TABLE `eh_resource_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '资源文件名称（不带后缀）',
  `extension` varchar(50) NOT NULL COMMENT '资源文件后缀',
  `type` tinyint(4) NOT NULL COMMENT '资源类型',
  `path` varchar(200) NOT NULL COMMENT '不带域名的绝对地址路径',
  `add_time` bigint(20) NOT NULL COMMENT '进入中转站的时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `eh_setting`
-- ----------------------------
DROP TABLE IF EXISTS `eh_setting`;
CREATE TABLE `eh_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `eh_admin_desktop` VALUES ('1','文章模块','article.png','1,5','0');
INSERT INTO `eh_admin_menu` VALUES ('1','0','文章管理',NULL,NULL,'0'), ('2','1','文章列表','list','/article/Admin','0'), ('3','1','添加文章','pencil','/article/Admin/create','0'), ('4','1','回收站','recycle','/article/Admin/recycle/','0'), ('5','0','设置',NULL,NULL,'0'), ('6','5','常规设置','cogs','/article/Admin/setting/','0');
