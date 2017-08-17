/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : swim

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-07-31 16:18:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pb_action`
-- ----------------------------
DROP TABLE IF EXISTS `pb_action`;
CREATE TABLE `pb_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text COMMENT '行为规则',
  `log` text COMMENT '日志规则',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='系统行为表';

-- ----------------------------
-- Records of pb_action
-- ----------------------------
INSERT INTO `pb_action` VALUES ('1', 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;', '[user|get_nickname]在[time|time_format]登录了后台', '1', '1', '1387181220');
INSERT INTO `pb_action` VALUES ('2', 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5', '', '2', '0', '1380173180');
INSERT INTO `pb_action` VALUES ('3', 'review', '评论', '评论积分+1，无限制', 'table:member|field:score|condition:uid={$self}|rule:score+1', '', '2', '1', '1383285646');
INSERT INTO `pb_action` VALUES ('4', 'add_document', '发表文档', '积分+10，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:5', '[user|get_nickname]在[time|time_format]发表了一篇文章。\r\n表[model]，记录编号[record]。', '2', '0', '1386139726');
INSERT INTO `pb_action` VALUES ('5', 'add_document_topic', '发表讨论', '积分+5，每天上限10次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10', '', '2', '0', '1383285551');
INSERT INTO `pb_action` VALUES ('6', 'update_config', '更新配置', '新增或修改或删除配置', '', '', '1', '1', '1383294988');
INSERT INTO `pb_action` VALUES ('7', 'update_model', '更新模型', '新增或修改模型', '', '', '1', '1', '1383295057');
INSERT INTO `pb_action` VALUES ('8', 'update_attribute', '更新属性', '新增或更新或删除属性', '', '', '1', '1', '1383295963');
INSERT INTO `pb_action` VALUES ('9', 'update_channel', '更新导航', '新增或修改或删除导航', '', '', '1', '1', '1383296301');
INSERT INTO `pb_action` VALUES ('10', 'update_menu', '更新菜单', '新增或修改或删除菜单', '', '', '1', '1', '1383296392');
INSERT INTO `pb_action` VALUES ('11', 'update_category', '更新分类', '新增或修改或删除分类', '', '', '1', '1', '1383296765');

-- ----------------------------
-- Table structure for `pb_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `pb_action_log`;
CREATE TABLE `pb_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`) USING BTREE,
  KEY `action_id_ix` (`action_id`) USING BTREE,
  KEY `user_id_ix` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of pb_action_log
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_addons`
-- ----------------------------
DROP TABLE IF EXISTS `pb_addons`;
CREATE TABLE `pb_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of pb_addons
-- ----------------------------
INSERT INTO `pb_addons` VALUES ('15', 'EditorForAdmin', '后台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"500px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1383126253', '0');
INSERT INTO `pb_addons` VALUES ('2', 'SiteStat', '站点统计信息', '统计站点的基础信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"1\",\"display\":\"1\",\"status\":\"0\"}', 'thinkphp', '0.1', '1379512015', '0');
INSERT INTO `pb_addons` VALUES ('3', 'DevTeam', '开发团队信息', '开发团队成员信息', '1', '{\"title\":\"pb\\u5f00\\u53d1\\u56e2\\u961f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379512022', '0');
INSERT INTO `pb_addons` VALUES ('4', 'SystemInfo', '系统环境信息', '用于显示一些服务器的信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379512036', '0');
INSERT INTO `pb_addons` VALUES ('5', 'Editor', '前台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"300px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1379830910', '0');
INSERT INTO `pb_addons` VALUES ('6', 'Attachment', '附件', '用于文档模型上传附件', '1', 'null', 'thinkphp', '0.1', '1379842319', '1');
INSERT INTO `pb_addons` VALUES ('9', 'SocialComment', '通用社交化评论', '集成了各种社交化评论插件，轻松集成到系统中。', '1', '{\"comment_type\":\"1\",\"comment_uid_youyan\":\"\",\"comment_short_name_duoshuo\":\"\",\"comment_data_list_duoshuo\":\"\"}', 'thinkphp', '0.1', '1380273962', '0');

-- ----------------------------
-- Table structure for `pb_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `pb_attachment`;
CREATE TABLE `pb_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '附件显示名',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件类型',
  `source` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小',
  `dir` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '上级目录ID',
  `sort` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `idx_record_status` (`record_id`,`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of pb_attachment
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `pb_attribute`;
CREATE TABLE `pb_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `validate_rule` varchar(255) NOT NULL DEFAULT '',
  `validate_time` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `error_info` varchar(100) NOT NULL DEFAULT '',
  `validate_type` varchar(25) NOT NULL DEFAULT '',
  `auto_rule` varchar(100) NOT NULL DEFAULT '',
  `auto_time` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `auto_type` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='模型属性表';

-- ----------------------------
-- Records of pb_attribute
-- ----------------------------
INSERT INTO `pb_attribute` VALUES ('1', 'uid', '用户ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', '0', '', '1', '0', '1', '1384508362', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('2', 'name', '标识', 'char(40) NOT NULL ', 'string', '', '同一根节点下标识不重复', '1', '', '1', '0', '1', '1383894743', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('3', 'title', '标题', 'char(80) NOT NULL ', 'string', '', '文档标题', '1', '', '1', '0', '1', '1383894778', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('4', 'category_id', '所属分类', 'int(10) unsigned NOT NULL ', 'string', '', '', '0', '', '1', '0', '1', '1384508336', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('5', 'description', '描述', 'char(140) NOT NULL ', 'textarea', '', '', '1', '', '1', '0', '1', '1383894927', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('6', 'root', '根节点', 'int(10) unsigned NOT NULL ', 'num', '0', '该文档的顶级文档编号', '0', '', '1', '0', '1', '1384508323', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('7', 'pid', '所属ID', 'int(10) unsigned NOT NULL ', 'num', '0', '父文档编号', '0', '', '1', '0', '1', '1384508543', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('8', 'model_id', '内容模型ID', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '该文档所对应的模型', '0', '', '1', '0', '1', '1384508350', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('9', 'type', '内容类型', 'tinyint(3) unsigned NOT NULL ', 'select', '2', '', '1', '1:目录\r\n2:主题\r\n3:段落', '1', '0', '1', '1384511157', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('10', 'position', '推荐位', 'smallint(5) unsigned NOT NULL ', 'checkbox', '0', '多个推荐则将其推荐值相加', '1', '[DOCUMENT_POSITION]', '1', '0', '1', '1383895640', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('11', 'link_id', '外链', 'int(10) unsigned NOT NULL ', 'num', '0', '0-非外链，大于0-外链ID,需要函数进行链接与编号的转换', '1', '', '1', '0', '1', '1383895757', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('12', 'cover_id', '封面', 'int(10) unsigned NOT NULL ', 'picture', '0', '0-无封面，大于0-封面图片ID，需要函数处理', '1', '', '1', '0', '1', '1384147827', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('13', 'display', '可见性', 'tinyint(3) unsigned NOT NULL ', 'radio', '1', '', '1', '0:不可见\r\n1:所有人可见', '1', '0', '1', '1386662271', '1383891233', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `pb_attribute` VALUES ('14', 'deadline', '截至时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '0-永久有效', '1', '', '1', '0', '1', '1387163248', '1383891233', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `pb_attribute` VALUES ('15', 'attach', '附件数量', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '', '0', '', '1', '0', '1', '1387260355', '1383891233', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `pb_attribute` VALUES ('16', 'view', '浏览量', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '1', '0', '1', '1383895835', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('17', 'comment', '评论数', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '1', '0', '1', '1383895846', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('18', 'extend', '扩展统计字段', 'int(10) unsigned NOT NULL ', 'num', '0', '根据需求自行使用', '0', '', '1', '0', '1', '1384508264', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('19', 'level', '优先级', 'int(10) unsigned NOT NULL ', 'num', '0', '越高排序越靠前', '1', '', '1', '0', '1', '1383895894', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('20', 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', '1', '', '1', '0', '1', '1383895903', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('21', 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', '0', '', '1', '0', '1', '1384508277', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('22', 'status', '数据状态', 'tinyint(4) NOT NULL ', 'radio', '0', '', '0', '-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核\r\n3:草稿', '1', '0', '1', '1384508496', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('23', 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', '0', '0:html\r\n1:ubb\r\n2:markdown', '2', '0', '1', '1384511049', '1383891243', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('24', 'content', '文章内容', 'text NOT NULL ', 'editor', '', '', '1', '', '2', '0', '1', '1383896225', '1383891243', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('25', 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '', '参照display方法参数的定义', '1', '', '2', '0', '1', '1383896190', '1383891243', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('26', 'bookmark', '收藏数', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '2', '0', '1', '1383896103', '1383891243', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('27', 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', '0', '0:html\r\n1:ubb\r\n2:markdown', '3', '0', '1', '1387260461', '1383891252', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `pb_attribute` VALUES ('28', 'content', '下载详细描述', 'text NOT NULL ', 'editor', '', '', '1', '', '3', '0', '1', '1383896438', '1383891252', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('29', 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '', '', '1', '', '3', '0', '1', '1383896429', '1383891252', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('30', 'file_id', '文件ID', 'int(10) unsigned NOT NULL ', 'file', '0', '需要函数处理', '1', '', '3', '0', '1', '1383896415', '1383891252', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('31', 'download', '下载次数', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '3', '0', '1', '1383896380', '1383891252', '', '0', '', '', '', '0', '');
INSERT INTO `pb_attribute` VALUES ('32', 'size', '文件大小', 'bigint(20) unsigned NOT NULL ', 'num', '0', '单位bit', '1', '', '3', '0', '1', '1383896371', '1383891252', '', '0', '', '', '', '0', '');

-- ----------------------------
-- Table structure for `pb_auth_extend`
-- ----------------------------
DROP TABLE IF EXISTS `pb_auth_extend`;
CREATE TABLE `pb_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`) USING BTREE,
  KEY `uid` (`group_id`) USING BTREE,
  KEY `group_id` (`extend_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';

-- ----------------------------
-- Records of pb_auth_extend
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `pb_auth_group`;
CREATE TABLE `pb_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pb_auth_group
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `pb_auth_group_access`;
CREATE TABLE `pb_auth_group_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pb_auth_group_access
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `pb_auth_rule`;
CREATE TABLE `pb_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=242 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pb_auth_rule
-- ----------------------------
INSERT INTO `pb_auth_rule` VALUES ('1', 'admin', '2', 'admin/Index/index', '首页', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('2', 'admin', '2', 'Admin/Article/index', '内容', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('3', 'admin', '2', 'admin/User/index', '后台用户', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('4', 'admin', '2', 'Admin/Addons/index', '扩展', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('5', 'admin', '2', 'Admin/Config/group', '系统', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('7', 'admin', '1', 'Admin/article/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('8', 'admin', '1', 'Admin/article/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('9', 'admin', '1', 'Admin/article/setStatus', '改变状态', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('10', 'admin', '1', 'Admin/article/update', '保存', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('11', 'admin', '1', 'Admin/article/autoSave', '保存草稿', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('12', 'admin', '1', 'Admin/article/move', '移动', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('13', 'admin', '1', 'Admin/article/copy', '复制', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('14', 'admin', '1', 'Admin/article/paste', '粘贴', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('15', 'admin', '1', 'Admin/article/permit', '还原', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('16', 'admin', '1', 'Admin/article/clear', '清空', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('17', 'admin', '1', 'Admin/Article/examine', '审核列表', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('18', 'admin', '1', 'Admin/article/recycle', '回收站', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('19', 'admin', '1', 'admin/User/addaction', '新增', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('20', 'admin', '1', 'admin/User/editaction', '编辑', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('21', 'admin', '1', 'Admin/User/saveAction', '保存用户行为', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('22', 'admin', '1', 'admin/User/setStatus', '变更状态', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('23', 'admin', '1', 'Admin/User/changeStatus?method=forbidUser', '禁用会员', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('24', 'admin', '1', 'Admin/User/changeStatus?method=resumeUser', '启用会员', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('25', 'admin', '1', 'Admin/User/changeStatus?method=deleteUser', '删除会员', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('26', 'admin', '1', 'admin/User/index', '用户信息', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('27', 'admin', '1', 'admin/User/action', '用户行为', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('28', 'admin', '1', 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('29', 'admin', '1', 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('30', 'admin', '1', 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('31', 'admin', '1', 'Admin/AuthManager/createGroup', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('32', 'admin', '1', 'Admin/AuthManager/editGroup', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('33', 'admin', '1', 'Admin/AuthManager/writeGroup', '保存用户组', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('34', 'admin', '1', 'Admin/AuthManager/group', '授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('35', 'admin', '1', 'Admin/AuthManager/access', '访问授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('36', 'admin', '1', 'Admin/AuthManager/user', '成员授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('37', 'admin', '1', 'Admin/AuthManager/removeFromGroup', '解除授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('38', 'admin', '1', 'Admin/AuthManager/addToGroup', '保存成员授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('39', 'admin', '1', 'Admin/AuthManager/category', '分类授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('40', 'admin', '1', 'Admin/AuthManager/addToCategory', '保存分类授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('41', 'admin', '1', 'Admin/AuthManager/index', '权限管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('42', 'admin', '1', 'Admin/Addons/create', '创建', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('43', 'admin', '1', 'Admin/Addons/checkForm', '检测创建', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('44', 'admin', '1', 'Admin/Addons/preview', '预览', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('45', 'admin', '1', 'Admin/Addons/build', '快速生成插件', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('46', 'admin', '1', 'Admin/Addons/config', '设置', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('47', 'admin', '1', 'Admin/Addons/disable', '禁用', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('48', 'admin', '1', 'Admin/Addons/enable', '启用', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('49', 'admin', '1', 'Admin/Addons/install', '安装', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('50', 'admin', '1', 'Admin/Addons/uninstall', '卸载', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('51', 'admin', '1', 'Admin/Addons/saveconfig', '更新配置', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('52', 'admin', '1', 'Admin/Addons/adminList', '插件后台列表', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('53', 'admin', '1', 'Admin/Addons/execute', 'URL方式访问插件', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('54', 'admin', '1', 'Admin/Addons/index', '插件管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('55', 'admin', '1', 'Admin/Addons/hooks', '钩子管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('56', 'admin', '1', 'Admin/model/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('57', 'admin', '1', 'Admin/model/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('58', 'admin', '1', 'Admin/model/setStatus', '改变状态', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('59', 'admin', '1', 'Admin/model/update', '保存数据', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('60', 'admin', '1', 'Admin/Model/index', '模型管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('61', 'admin', '1', 'admin/Config/edit', '编辑', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('62', 'admin', '1', 'admin/Config/del', '删除', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('63', 'admin', '1', 'admin/Config/add', '新增', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('64', 'admin', '1', 'admin/Config/save', '保存', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('65', 'admin', '1', 'admin/Config/group', '网站配置', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('66', 'admin', '1', 'admin/Config/index', '配置属性', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('67', 'admin', '1', 'Admin/Channel/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('68', 'admin', '1', 'Admin/Channel/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('69', 'admin', '1', 'Admin/Channel/del', '删除', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('70', 'admin', '1', 'Admin/Channel/index', '导航管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('71', 'admin', '1', 'Admin/Category/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('72', 'admin', '1', 'Admin/Category/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('73', 'admin', '1', 'Admin/Category/remove', '删除', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('74', 'admin', '1', 'Admin/Category/index', '分类管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('75', 'admin', '1', 'Admin/file/upload', '上传控件', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('76', 'admin', '1', 'Admin/file/uploadPicture', '上传图片', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('77', 'admin', '1', 'Admin/file/download', '下载', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('94', 'admin', '1', 'Admin/AuthManager/modelauth', '模型授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('79', 'admin', '1', 'Admin/article/batchOperate', '导入', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('80', 'admin', '1', 'Admin/Database/index?type=export', '备份数据库', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('81', 'admin', '1', 'Admin/Database/index?type=import', '还原数据库', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('82', 'admin', '1', 'admin/Database/export', '备份', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('83', 'admin', '1', 'admin/Database/optimize', '优化表', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('84', 'admin', '1', 'admin/Database/repair', '修复表', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('86', 'admin', '1', 'admin/Database/import', '恢复', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('87', 'admin', '1', 'admin/Database/del', '删除', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('88', 'admin', '1', 'admin/User/add', '新增', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('89', 'admin', '1', 'Admin/Attribute/index', '属性管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('90', 'admin', '1', 'Admin/Attribute/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('91', 'admin', '1', 'Admin/Attribute/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('92', 'admin', '1', 'Admin/Attribute/setStatus', '改变状态', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('93', 'admin', '1', 'Admin/Attribute/update', '保存数据', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('95', 'admin', '1', 'Admin/AuthManager/addToModel', '保存模型授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('96', 'admin', '1', 'Admin/Category/move', '移动', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('97', 'admin', '1', 'Admin/Category/merge', '合并', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('98', 'admin', '1', 'Admin/Config/menu', '后台菜单管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('99', 'admin', '1', 'Admin/Article/mydocument', '内容', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('100', 'admin', '1', 'admin/Menu/index', '菜单管理', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('101', 'admin', '1', 'Admin/other', '其他', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('102', 'admin', '1', 'admin/Menu/add', '新增', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('103', 'admin', '1', 'admin/Menu/edit', '编辑', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('104', 'admin', '1', 'Admin/Think/lists?model=article', '文章管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('105', 'admin', '1', 'Admin/Think/lists?model=download', '下载管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('106', 'admin', '1', 'Admin/Think/lists?model=config', '配置管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('107', 'admin', '1', 'admin/Action/actionlog', '行为日志', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('108', 'admin', '1', 'Admin/User/updatePassword', '修改密码', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('109', 'admin', '1', 'Admin/User/updateNickname', '修改昵称', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('110', 'admin', '1', 'Admin/action/edit', '查看行为日志', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('205', 'admin', '1', 'Admin/think/add', '新增数据', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('111', 'admin', '2', 'Admin/article/index', '文档列表', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('112', 'admin', '2', 'Admin/article/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('113', 'admin', '2', 'Admin/article/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('114', 'admin', '2', 'Admin/article/setStatus', '改变状态', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('115', 'admin', '2', 'Admin/article/update', '保存', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('116', 'admin', '2', 'Admin/article/autoSave', '保存草稿', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('117', 'admin', '2', 'Admin/article/move', '移动', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('118', 'admin', '2', 'Admin/article/copy', '复制', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('119', 'admin', '2', 'Admin/article/paste', '粘贴', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('120', 'admin', '2', 'Admin/article/batchOperate', '导入', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('121', 'admin', '2', 'Admin/article/recycle', '回收站', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('122', 'admin', '2', 'Admin/article/permit', '还原', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('123', 'admin', '2', 'Admin/article/clear', '清空', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('124', 'admin', '2', 'Admin/User/add', '新增用户', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('125', 'admin', '2', 'Admin/User/action', '用户行为', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('126', 'admin', '2', 'Admin/User/addAction', '新增用户行为', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('127', 'admin', '2', 'Admin/User/editAction', '编辑用户行为', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('128', 'admin', '2', 'Admin/User/saveAction', '保存用户行为', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('129', 'admin', '2', 'Admin/User/setStatus', '变更行为状态', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('130', 'admin', '2', 'Admin/User/changeStatus?method=forbidUser', '禁用会员', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('131', 'admin', '2', 'Admin/User/changeStatus?method=resumeUser', '启用会员', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('132', 'admin', '2', 'Admin/User/changeStatus?method=deleteUser', '删除会员', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('133', 'admin', '2', 'Admin/AuthManager/index', '权限管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('134', 'admin', '2', 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('135', 'admin', '2', 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('136', 'admin', '2', 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('137', 'admin', '2', 'Admin/AuthManager/createGroup', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('138', 'admin', '2', 'Admin/AuthManager/editGroup', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('139', 'admin', '2', 'Admin/AuthManager/writeGroup', '保存用户组', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('140', 'admin', '2', 'Admin/AuthManager/group', '授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('141', 'admin', '2', 'Admin/AuthManager/access', '访问授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('142', 'admin', '2', 'Admin/AuthManager/user', '成员授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('143', 'admin', '2', 'Admin/AuthManager/removeFromGroup', '解除授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('144', 'admin', '2', 'Admin/AuthManager/addToGroup', '保存成员授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('145', 'admin', '2', 'Admin/AuthManager/category', '分类授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('146', 'admin', '2', 'Admin/AuthManager/addToCategory', '保存分类授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('147', 'admin', '2', 'Admin/AuthManager/modelauth', '模型授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('148', 'admin', '2', 'Admin/AuthManager/addToModel', '保存模型授权', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('149', 'admin', '2', 'Admin/Addons/create', '创建', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('150', 'admin', '2', 'Admin/Addons/checkForm', '检测创建', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('151', 'admin', '2', 'Admin/Addons/preview', '预览', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('152', 'admin', '2', 'Admin/Addons/build', '快速生成插件', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('153', 'admin', '2', 'Admin/Addons/config', '设置', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('154', 'admin', '2', 'Admin/Addons/disable', '禁用', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('155', 'admin', '2', 'Admin/Addons/enable', '启用', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('156', 'admin', '2', 'Admin/Addons/install', '安装', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('157', 'admin', '2', 'Admin/Addons/uninstall', '卸载', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('158', 'admin', '2', 'Admin/Addons/saveconfig', '更新配置', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('159', 'admin', '2', 'Admin/Addons/adminList', '插件后台列表', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('160', 'admin', '2', 'Admin/Addons/execute', 'URL方式访问插件', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('161', 'admin', '2', 'Admin/Addons/hooks', '钩子管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('162', 'admin', '2', 'Admin/Model/index', '模型管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('163', 'admin', '2', 'Admin/model/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('164', 'admin', '2', 'Admin/model/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('165', 'admin', '2', 'Admin/model/setStatus', '改变状态', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('166', 'admin', '2', 'Admin/model/update', '保存数据', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('167', 'admin', '2', 'Admin/Attribute/index', '属性管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('168', 'admin', '2', 'Admin/Attribute/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('169', 'admin', '2', 'Admin/Attribute/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('170', 'admin', '2', 'Admin/Attribute/setStatus', '改变状态', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('171', 'admin', '2', 'Admin/Attribute/update', '保存数据', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('172', 'admin', '2', 'Admin/Config/index', '配置管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('173', 'admin', '2', 'Admin/Config/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('174', 'admin', '2', 'Admin/Config/del', '删除', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('175', 'admin', '2', 'Admin/Config/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('176', 'admin', '2', 'Admin/Config/save', '保存', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('177', 'admin', '2', 'Admin/Menu/index', '菜单管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('178', 'admin', '2', 'Admin/Channel/index', '导航管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('179', 'admin', '2', 'Admin/Channel/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('180', 'admin', '2', 'Admin/Channel/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('181', 'admin', '2', 'Admin/Channel/del', '删除', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('182', 'admin', '2', 'Admin/Category/index', '分类管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('183', 'admin', '2', 'Admin/Category/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('184', 'admin', '2', 'Admin/Category/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('185', 'admin', '2', 'Admin/Category/remove', '删除', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('186', 'admin', '2', 'Admin/Category/move', '移动', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('187', 'admin', '2', 'Admin/Category/merge', '合并', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('188', 'admin', '2', 'Admin/Database/index?type=export', '备份数据库', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('189', 'admin', '2', 'Admin/Database/export', '备份', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('190', 'admin', '2', 'Admin/Database/optimize', '优化表', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('191', 'admin', '2', 'Admin/Database/repair', '修复表', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('192', 'admin', '2', 'Admin/Database/index?type=import', '还原数据库', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('193', 'admin', '2', 'Admin/Database/import', '恢复', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('194', 'admin', '2', 'Admin/Database/del', '删除', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('195', 'admin', '2', 'Admin/other', '其他', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('196', 'admin', '2', 'Admin/Menu/add', '新增', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('197', 'admin', '2', 'Admin/Menu/edit', '编辑', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('198', 'admin', '2', 'Admin/Think/lists?model=article', '应用', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('199', 'admin', '2', 'Admin/Think/lists?model=download', '下载管理', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('200', 'admin', '2', 'Admin/Think/lists?model=config', '应用', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('201', 'admin', '2', 'Admin/Action/actionlog', '行为日志', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('202', 'admin', '2', 'Admin/User/updatePassword', '修改密码', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('203', 'admin', '2', 'Admin/User/updateNickname', '修改昵称', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('204', 'admin', '2', 'Admin/action/edit', '查看行为日志', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('206', 'admin', '1', 'Admin/think/edit', '编辑数据', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('207', 'admin', '1', 'admin/Menu/import', '导入	', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('208', 'admin', '1', 'Admin/Model/generate', '生成', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('209', 'admin', '1', 'Admin/Addons/addHook', '新增钩子', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('210', 'admin', '1', 'Admin/Addons/edithook', '编辑钩子', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('211', 'admin', '1', 'Admin/Article/sort', '文档排序', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('212', 'admin', '1', 'admin/Config/sort', '排序', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('213', 'admin', '1', 'admin/Menu/sort', '排序', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('214', 'admin', '1', 'Admin/Channel/sort', '排序', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('215', 'admin', '1', 'Admin/Category/operate/type/move', '移动', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('216', 'admin', '1', 'Admin/Category/operate/type/merge', '合并', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('217', 'admin', '1', 'Admin/article/index', '文档列表', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('218', 'admin', '1', 'Admin/think/lists', '数据列表', '-1', '');
INSERT INTO `pb_auth_rule` VALUES ('219', 'admin', '1', 'admin/Menu/del', '删除', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('220', 'admin', '1', 'admin/Menu/toogleHide', '设置隐藏', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('221', 'admin', '1', 'admin/Menu/toogleDev', '设置开发者', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('222', 'admin', '1', 'admin/Menu/getinfo', '获取信息', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('223', 'admin', '1', 'admin/Auth/index', '权限管理', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('224', 'admin', '1', 'admin/User/edit', '编辑', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('225', 'admin', '1', 'admin/User/del', '删除', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('226', 'admin', '1', 'admin/User/changeStatus', '修改状态', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('227', 'admin', '1', 'admin/User/delaction', '删除', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('228', 'admin', '1', 'admin/Auth/add', '新增', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('229', 'admin', '1', 'admin/Auth/edit', '编辑', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('230', 'admin', '1', 'admin/Auth/del', '删除', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('231', 'admin', '1', 'admin/Auth/changeStatus', '修改状态', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('232', 'admin', '1', 'admin/Auth/user', '成员授权', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('233', 'admin', '1', 'admin/Auth/access', '访问授权', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('234', 'admin', '1', 'admin/Auth/group', '组授权', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('235', 'admin', '1', 'admin/Auth/addToGroup', '保存成员授权', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('236', 'admin', '1', 'admin/Auth/removeFromGroup', '解除授权', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('237', 'admin', '1', 'admin/action/view', '查看行为日志', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('238', 'admin', '1', 'admin/Wechat/user', '用户列表', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('239', 'admin', '1', 'admin/Wechat/department', '部门', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('240', 'admin', '1', 'admin/Wechat/tag', '标签', '1', '');
INSERT INTO `pb_auth_rule` VALUES ('241', 'admin', '1', 'admin/Wechat/synchronize', '同步通讯录', '1', '');

-- ----------------------------
-- Table structure for `pb_browse`
-- ----------------------------
DROP TABLE IF EXISTS `pb_browse`;
CREATE TABLE `pb_browse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL COMMENT '表类型,1learn 2redfilm 3redmusic 4redbook 5news 6notice 7special 8 tutor_course',
  `table` varchar(50) DEFAULT NULL COMMENT '表名',
  `aid` int(11) DEFAULT NULL COMMENT '所属文章id',
  `uid` varchar(255) DEFAULT NULL COMMENT '用户id',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `score` tinyint(4) DEFAULT '1' COMMENT '规定分数,浏览一分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='浏览记录表';

-- ----------------------------
-- Records of pb_browse
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_category`
-- ----------------------------
DROP TABLE IF EXISTS `pb_category`;
CREATE TABLE `pb_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL COMMENT '标志',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `list_row` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '列表每页行数',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `template_index` varchar(100) NOT NULL DEFAULT '' COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL DEFAULT '' COMMENT '列表绑定模型',
  `model_sub` varchar(100) NOT NULL DEFAULT '' COMMENT '子文档绑定模型',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '允许发布的内容类型',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `allow_publish` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发布内容',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  `reply` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许回复',
  `check` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '发布的文章是否需要审核',
  `reply_model` varchar(100) NOT NULL DEFAULT '',
  `extend` text COMMENT '扩展设置',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  `icon` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类图标',
  `groups` varchar(255) NOT NULL DEFAULT '' COMMENT '分组定义',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
-- Records of pb_category
-- ----------------------------
INSERT INTO `pb_category` VALUES ('1', 'blog', '博客', '0', '0', '10', '', '', '', '', '', '', '', '2,3', '2', '2,1', '0', '0', '1', '0', '0', '1', '', '1379474947', '1382701539', '1', '0', '');
INSERT INTO `pb_category` VALUES ('2', 'default_blog', '默认分类', '1', '1', '10', '', '', '', '', '', '', '', '2,3', '2', '2,1,3', '0', '1', '1', '0', '1', '1', '', '1379475028', '1386839751', '1', '0', '');

-- ----------------------------
-- Table structure for `pb_channel`
-- ----------------------------
DROP TABLE IF EXISTS `pb_channel`;
CREATE TABLE `pb_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pb_channel
-- ----------------------------
INSERT INTO `pb_channel` VALUES ('1', '0', '首页', 'Index/index', '1', '1379475111', '1379923177', '1', '0');
INSERT INTO `pb_channel` VALUES ('2', '0', '博客', 'Article/index?category=blog', '2', '1379475131', '1379483713', '1', '0');
INSERT INTO `pb_channel` VALUES ('3', '0', '官网', 'http://www.pb.cn', '3', '1379475154', '1387163458', '1', '0');

-- ----------------------------
-- Table structure for `pb_comment`
-- ----------------------------
DROP TABLE IF EXISTS `pb_comment`;
CREATE TABLE `pb_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text COMMENT '评论内容',
  `type` tinyint(4) DEFAULT NULL COMMENT '评论类型，1第一聚焦 2支部活动 3两学一做 4意见反馈 5红色电影 6红色歌曲 7经典书籍',
  `table` varchar(50) DEFAULT NULL COMMENT '表名',
  `aid` int(11) DEFAULT NULL COMMENT '文章id',
  `uid` varchar(255) DEFAULT NULL COMMENT '用户id',
  `comments` mediumint(9) DEFAULT '0' COMMENT '评论数',
  `likes` mediumint(9) DEFAULT '0' COMMENT '点赞数',
  `create_time` int(11) DEFAULT NULL COMMENT '评论时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '-1 删除 0正常',
  `score` int(11) DEFAULT '1' COMMENT '得分标准，评论1分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户评论表';

-- ----------------------------
-- Records of pb_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_config`
-- ----------------------------
DROP TABLE IF EXISTS `pb_config`;
CREATE TABLE `pb_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `group` (`group`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pb_config
-- ----------------------------
INSERT INTO `pb_config` VALUES ('1', 'WEB_SITE_TITLE', '1', '网站标题', '1', '', '网站标题前台显示标题', '1378898976', '1379235274', '1', '机关党建', '0');
INSERT INTO `pb_config` VALUES ('2', 'WEB_SITE_DESCRIPTION', '2', '网站描述', '1', '', '网站搜索引擎描述', '1378898976', '1379235841', '1', '机关党建', '1');
INSERT INTO `pb_config` VALUES ('3', 'WEB_SITE_KEYWORD', '2', '网站关键字', '1', '', '网站搜索引擎关键字', '1378898976', '1381390100', '1', '机关党建,共产党,', '8');
INSERT INTO `pb_config` VALUES ('4', 'WEB_SITE_CLOSE', '4', '关闭站点', '1', '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', '1378898976', '1379235296', '1', '1', '1');
INSERT INTO `pb_config` VALUES ('9', 'CONFIG_TYPE_LIST', '3', '配置类型列表', '4', '', '主要用于数据解析和页面表单的生成', '1378898976', '1379235348', '1', '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举', '2');
INSERT INTO `pb_config` VALUES ('10', 'WEB_SITE_ICP', '1', '网站备案号', '1', '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', '1378900335', '1379235859', '1', '', '9');
INSERT INTO `pb_config` VALUES ('11', 'DOCUMENT_POSITION', '3', '文档推荐位', '2', '', '文档推荐位，推荐到多个位置KEY值相加即可', '1379053380', '1379235329', '1', '1:列表推荐\r\n2:频道推荐\r\n4:首页推荐', '3');
INSERT INTO `pb_config` VALUES ('12', 'DOCUMENT_DISPLAY', '3', '文档可见性', '2', '', '文章可见性仅影响前台显示，后台不收影响', '1379056370', '1379235322', '1', '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', '4');
INSERT INTO `pb_config` VALUES ('13', 'COLOR_STYLE', '4', '后台色系', '1', 'default_color:默认\r\nblue_color:紫罗兰', '后台颜色风格', '1379122533', '1379235904', '1', 'default_color', '10');
INSERT INTO `pb_config` VALUES ('20', 'CONFIG_GROUP_LIST', '3', '配置分组', '4', '', '配置分组', '1379228036', '1384418383', '1', '1:基本\r\n2:内容\r\n3:用户\r\n4:系统', '4');
INSERT INTO `pb_config` VALUES ('21', 'HOOKS_TYPE', '3', '钩子的类型', '4', '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', '1379313397', '1379313407', '1', '1:视图\r\n2:控制器', '6');
INSERT INTO `pb_config` VALUES ('22', 'AUTH_CONFIG', '3', 'Auth配置', '4', '', '自定义Auth.class.php类配置', '1379409310', '1379409564', '1', 'AUTH_ON:1\r\nAUTH_TYPE:2', '8');
INSERT INTO `pb_config` VALUES ('23', 'OPEN_DRAFTBOX', '4', '是否开启草稿功能', '2', '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', '1379484332', '1379484591', '1', '1', '1');
INSERT INTO `pb_config` VALUES ('24', 'DRAFT_AOTOSAVE_INTERVAL', '0', '自动保存草稿时间', '2', '', '自动保存草稿的时间间隔，单位：秒', '1379484574', '1386143323', '1', '60', '2');
INSERT INTO `pb_config` VALUES ('25', 'LIST_ROWS', '0', '后台每页记录数', '2', '', '后台数据每页显示记录数', '1379503896', '1380427745', '1', '10', '10');
INSERT INTO `pb_config` VALUES ('26', 'USER_ALLOW_REGISTER', '4', '是否允许用户注册', '3', '0:关闭注册\r\n1:允许注册', '是否开放用户注册', '1379504487', '1379504580', '1', '1', '3');
INSERT INTO `pb_config` VALUES ('27', 'CODEMIRROR_THEME', '4', '预览插件的CodeMirror主题', '4', '3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight', '详情见CodeMirror官网', '1379814385', '1384740813', '1', 'ambiance', '3');
INSERT INTO `pb_config` VALUES ('28', 'DATA_BACKUP_PATH', '1', '数据库备份根路径', '4', '', '路径必须以 / 结尾', '1381482411', '1381482411', '1', './Data/', '5');
INSERT INTO `pb_config` VALUES ('29', 'DATA_BACKUP_PART_SIZE', '0', '数据库备份卷大小', '4', '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', '1381482488', '1381729564', '1', '20971520', '7');
INSERT INTO `pb_config` VALUES ('30', 'DATA_BACKUP_COMPRESS', '4', '数据库备份文件是否启用压缩', '4', '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', '1381713345', '1381729544', '1', '1', '9');
INSERT INTO `pb_config` VALUES ('31', 'DATA_BACKUP_COMPRESS_LEVEL', '4', '数据库备份文件压缩级别', '4', '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '1381713408', '1381713408', '1', '9', '10');
INSERT INTO `pb_config` VALUES ('32', 'DEVELOP_MODE', '4', '开启开发者模式', '4', '0:关闭\r\n1:开启', '是否开启开发者模式', '1383105995', '1383291877', '1', '1', '11');
INSERT INTO `pb_config` VALUES ('33', 'ALLOW_VISIT', '3', '不受限控制器方法', '0', '', '', '1386644047', '1386644741', '1', '0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadpicture', '0');
INSERT INTO `pb_config` VALUES ('34', 'DENY_VISIT', '3', '超管专限控制器方法', '0', '', '仅超级管理员可访问的控制器方法', '1386644141', '1386644659', '1', '0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree', '0');
INSERT INTO `pb_config` VALUES ('35', 'REPLY_LIST_ROWS', '0', '回复列表每页条数', '2', '', '', '1386645376', '1387178083', '1', '10', '0');
INSERT INTO `pb_config` VALUES ('36', 'ADMIN_ALLOW_IP', '2', '后台允许访问IP', '4', '', '多个用逗号分隔，如果不配置表示不限制IP访问', '1387165454', '1387165553', '1', '', '12');
INSERT INTO `pb_config` VALUES ('37', 'SHOW_PAGE_TRACE', '4', '是否显示页面Trace', '4', '0:关闭\r\n1:开启', '是否显示页面Trace信息', '1387165685', '1387165685', '1', '0', '1');

-- ----------------------------
-- Table structure for `pb_document`
-- ----------------------------
DROP TABLE IF EXISTS `pb_document`;
CREATE TABLE `pb_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `category_id` int(10) unsigned NOT NULL COMMENT '所属分类',
  `group_id` smallint(3) unsigned NOT NULL COMMENT '所属分组',
  `description` char(140) NOT NULL DEFAULT '' COMMENT '描述',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属ID',
  `model_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容模型ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '内容类型',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '可见性',
  `deadline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截至时间',
  `attach` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展统计字段',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `idx_category_status` (`category_id`,`status`) USING BTREE,
  KEY `idx_status_type_pid` (`status`,`uid`,`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文档模型基础表';

-- ----------------------------
-- Records of pb_document
-- ----------------------------
INSERT INTO `pb_document` VALUES ('1', '1', '', 'pb1.1开发版发布', '2', '0', '期待已久的pb最新版发布', '0', '0', '2', '2', '0', '0', '0', '1', '0', '0', '8', '0', '0', '0', '1406001413', '1406001413', '1');

-- ----------------------------
-- Table structure for `pb_document_article`
-- ----------------------------
DROP TABLE IF EXISTS `pb_document_article`;
CREATE TABLE `pb_document_article` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表';

-- ----------------------------
-- Records of pb_document_article
-- ----------------------------
INSERT INTO `pb_document_article` VALUES ('1', '0', '<h1>\r\n	pb1.1开发版发布&nbsp;\r\n</h1>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>pb是一个开源的内容管理框架，基于最新的ThinkPHP3.2版本开发，提供更方便、更安全的WEB应用开发体验，采用了全新的架构设计和命名空间机制，融合了模块化、驱动化和插件化的设计理念于一体，开启了国内WEB应用傻瓜式开发的新潮流。&nbsp;</strong> \r\n</p>\r\n<h2>\r\n	主要特性：\r\n</h2>\r\n<p>\r\n	1. 基于ThinkPHP最新3.2版本。\r\n</p>\r\n<p>\r\n	2. 模块化：全新的架构和模块化的开发机制，便于灵活扩展和二次开发。&nbsp;\r\n</p>\r\n<p>\r\n	3. 文档模型/分类体系：通过和文档模型绑定，以及不同的文档类型，不同分类可以实现差异化的功能，轻松实现诸如资讯、下载、讨论和图片等功能。\r\n</p>\r\n<p>\r\n	4. 开源免费：pb遵循Apache2开源协议,免费提供使用。&nbsp;\r\n</p>\r\n<p>\r\n	5. 用户行为：支持自定义用户行为，可以对单个用户或者群体用户的行为进行记录及分享，为您的运营决策提供有效参考数据。\r\n</p>\r\n<p>\r\n	6. 云端部署：通过驱动的方式可以轻松支持平台的部署，让您的网站无缝迁移，内置已经支持SAE和BAE3.0。\r\n</p>\r\n<p>\r\n	7. 云服务支持：即将启动支持云存储、云安全、云过滤和云统计等服务，更多贴心的服务让您的网站更安心。\r\n</p>\r\n<p>\r\n	8. 安全稳健：提供稳健的安全策略，包括备份恢复、容错、防止恶意攻击登录，网页防篡改等多项安全管理功能，保证系统安全，可靠、稳定的运行。&nbsp;\r\n</p>\r\n<p>\r\n	9. 应用仓库：官方应用仓库拥有大量来自第三方插件和应用模块、模板主题，有众多来自开源社区的贡献，让您的网站“One”美无缺。&nbsp;\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>&nbsp;pb集成了一个完善的后台管理体系和前台模板标签系统，让你轻松管理数据和进行前台网站的标签式开发。&nbsp;</strong> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<h2>\r\n	后台主要功能：\r\n</h2>\r\n<p>\r\n	1. 用户Passport系统\r\n</p>\r\n<p>\r\n	2. 配置管理系统&nbsp;\r\n</p>\r\n<p>\r\n	3. 权限控制系统\r\n</p>\r\n<p>\r\n	4. 后台建模系统&nbsp;\r\n</p>\r\n<p>\r\n	5. 多级分类系统&nbsp;\r\n</p>\r\n<p>\r\n	6. 用户行为系统&nbsp;\r\n</p>\r\n<p>\r\n	7. 钩子和插件系统\r\n</p>\r\n<p>\r\n	8. 系统日志系统&nbsp;\r\n</p>\r\n<p>\r\n	9. 数据备份和还原\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	&nbsp;[ 官方下载：&nbsp;<a href=\"http://www.pb.cn/download.html\" target=\"_blank\">http://www.pb.cn/download.html</a>&nbsp;&nbsp;开发手册：<a href=\"http://document.pb.cn/\" target=\"_blank\">http://document.pb.cn/</a>&nbsp;]&nbsp;\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>pb开发团队 2013~2014</strong> \r\n</p>', '', '0');

-- ----------------------------
-- Table structure for `pb_document_download`
-- ----------------------------
DROP TABLE IF EXISTS `pb_document_download`;
CREATE TABLE `pb_document_download` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型下载表';

-- ----------------------------
-- Records of pb_document_download
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_file`
-- ----------------------------
DROP TABLE IF EXISTS `pb_file`;
CREATE TABLE `pb_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` varchar(100) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` char(30) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `type` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '远程地址',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_md5` (`md5`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件表';

-- ----------------------------
-- Records of pb_file
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_hooks`
-- ----------------------------
DROP TABLE IF EXISTS `pb_hooks`;
CREATE TABLE `pb_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pb_hooks
-- ----------------------------
INSERT INTO `pb_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', '', '1');
INSERT INTO `pb_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '0', 'ReturnTop', '1');
INSERT INTO `pb_hooks` VALUES ('3', 'documentEditForm', '添加编辑表单的 扩展内容钩子', '1', '0', 'Attachment', '1');
INSERT INTO `pb_hooks` VALUES ('4', 'documentDetailAfter', '文档末尾显示', '1', '0', 'Attachment,SocialComment', '1');
INSERT INTO `pb_hooks` VALUES ('5', 'documentDetailBefore', '页面内容前显示用钩子', '1', '0', '', '1');
INSERT INTO `pb_hooks` VALUES ('6', 'documentSaveComplete', '保存文档数据后的扩展钩子', '2', '0', 'Attachment', '1');
INSERT INTO `pb_hooks` VALUES ('7', 'documentEditFormContent', '添加编辑表单的内容显示钩子', '1', '0', 'Editor', '1');
INSERT INTO `pb_hooks` VALUES ('8', 'adminArticleEdit', '后台内容编辑页编辑器', '1', '1378982734', 'EditorForAdmin', '1');
INSERT INTO `pb_hooks` VALUES ('13', 'AdminIndex', '首页小格子个性化显示', '1', '1382596073', 'SiteStat,SystemInfo,DevTeam', '1');
INSERT INTO `pb_hooks` VALUES ('14', 'topicComment', '评论提交方式扩展钩子。', '1', '1380163518', 'Editor', '1');
INSERT INTO `pb_hooks` VALUES ('16', 'app_begin', '应用开始', '2', '1384481614', '', '1');

-- ----------------------------
-- Table structure for `pb_like`
-- ----------------------------
DROP TABLE IF EXISTS `pb_like`;
CREATE TABLE `pb_like` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) DEFAULT NULL COMMENT '表类别,1评论,2,3,4,5,',
  `table` varchar(50) DEFAULT NULL COMMENT '表名',
  `aid` int(11) DEFAULT NULL COMMENT '文章id',
  `uid` varchar(100) DEFAULT NULL COMMENT '用户id',
  `create_time` int(11) DEFAULT NULL COMMENT '点赞时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '-1 删除 0正常',
  `score` int(11) DEFAULT '1' COMMENT '得分标准。点赞1分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='文章及评论点赞表';

-- ----------------------------
-- Records of pb_like
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_log`
-- ----------------------------
DROP TABLE IF EXISTS `pb_log`;
CREATE TABLE `pb_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) NOT NULL COMMENT '用户ID',
  `department_id` varchar(50) DEFAULT NULL COMMENT '部门ID',
  `create_time` int(11) NOT NULL COMMENT '发生时间',
  `event` varchar(30) DEFAULT '' COMMENT '类型，表模型名字',
  `source` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='统计分析表';

-- ----------------------------
-- Records of pb_log
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_member`
-- ----------------------------
DROP TABLE IF EXISTS `pb_member`;
CREATE TABLE `pb_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `login_total` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '会员状态',
  `reg_time` int(11) DEFAULT NULL,
  `reg_ip` bigint(20) DEFAULT NULL,
  `last_login_time` int(11) DEFAULT NULL,
  `last_login_ip` bigint(20) DEFAULT NULL,
  `check_number` varchar(11) DEFAULT NULL,
  `active_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of pb_member
-- ----------------------------
INSERT INTO `pb_member` VALUES ('1', 'admin', '0', '2016-05-23', '', '0', null, '1', null, null, '1501468166', '605614176', null, null);

-- ----------------------------
-- Table structure for `pb_menu`
-- ----------------------------
DROP TABLE IF EXISTS `pb_menu`;
CREATE TABLE `pb_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `icon` varchar(50) DEFAULT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pb_menu
-- ----------------------------
INSERT INTO `pb_menu` VALUES ('1', '首页', 'fa-th-large', '0', '1', 'Index/index', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('5', '通讯录', 'fa-users', '0', '7', 'Wechat/index', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('6', '系统设置', 'fa-cog', '0', '8', 'System/index', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('7', '网站配置', '', '6', '0', 'Config/group', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('8', '配置属性', '', '6', '0', 'Config/index', '1', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('9', '菜单管理', '', '6', '0', 'Menu/index', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('10', '备份数据库', '', '6', '0', 'Database/export', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('11', '还原数据库', '', '6', '0', 'Database/import', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('12', '新增', '', '8', '0', 'Config/add', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('13', '编辑', '', '8', '0', 'Config/edit', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('14', '删除', '', '8', '0', 'Config/del', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('15', '保存', '', '8', '0', 'Config/save', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('16', '排序', '', '8', '0', 'Config/sort', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('17', '新增', '', '9', '0', 'Menu/add', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('18', '编辑', '', '9', '0', 'Menu/edit', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('19', '导入	', '', '9', '0', 'Menu/import', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('20', '排序', '', '9', '0', 'Menu/sort', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('21', '删除', '', '9', '0', 'Menu/del', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('22', '设置隐藏', '', '9', '0', 'Menu/toogleHide', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('23', '设置开发者', '', '9', '0', 'Menu/toogleDev', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('24', '获取信息', '', '9', '0', 'Menu/getinfo', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('25', '后台用户', 'fa-user', '0', '9', 'User/index', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('26', '用户信息', '', '25', '0', 'User/index', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('27', '用户行为', '', '25', '0', 'User/action', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('28', '权限管理', '', '25', '0', 'Auth/index', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('29', '行为日志', '', '25', '0', 'Action/actionlog', '1', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('30', '新增', '', '26', '0', 'User/add', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('31', '编辑', '', '26', '0', 'User/edit', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('32', '删除', '', '26', '0', 'User/del', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('33', '修改状态', '', '26', '0', 'User/changeStatus', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('34', '新增', '', '27', '0', 'User/addaction', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('35', '编辑', '', '27', '0', 'User/editaction', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('36', '删除', '', '27', '0', 'User/delaction', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('37', '变更状态', '', '27', '0', 'User/setStatus', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('38', '新增', '', '28', '0', 'Auth/add', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('39', '编辑', '', '28', '0', 'Auth/edit', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('40', '删除', '', '28', '0', 'Auth/del', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('41', '修改状态', '', '28', '0', 'Auth/changeStatus', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('42', '成员授权', '', '28', '0', 'Auth/user', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('43', '访问授权', '', '28', '0', 'Auth/access', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('44', '组授权', '', '28', '0', 'Auth/group', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('45', '保存成员授权', '', '28', '0', 'Auth/addToGroup', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('46', '解除授权', '', '28', '0', 'Auth/removeFromGroup', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('47', '查看行为日志', '', '29', '0', 'action/view', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('48', '备份', '', '10', '0', 'Database/export', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('49', '优化表', '', '10', '0', 'Database/optimize', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('50', '修复表', '', '10', '0', 'Database/repair', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('51', '恢复', '', '11', '0', 'Database/import', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('52', '删除', '', '11', '0', 'Database/del', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('53', '用户列表', '', '5', '0', 'Wechat/user', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('54', '部门', '', '5', '0', 'Wechat/department', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('55', '标签', '', '5', '0', 'Wechat/tag', '0', '', '', '0', '1');
INSERT INTO `pb_menu` VALUES ('56', '同步通讯录', '', '5', '0', 'Wechat/synchronize', '1', '', '', '0', '1');

-- ----------------------------
-- Table structure for `pb_message`
-- ----------------------------
DROP TABLE IF EXISTS `pb_message`;
CREATE TABLE `pb_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL COMMENT '被回复内容',
  `content` varchar(1000) DEFAULT NULL COMMENT '内容',
  `receive_id` varchar(100) DEFAULT NULL COMMENT '收件人id',
  `activity_id` int(11) DEFAULT NULL COMMENT '活动id',
  `focus_id` int(11) DEFAULT NULL COMMENT '聚焦id',
  `topic_id` int(11) DEFAULT NULL COMMENT '话题id',
  `course_id` int(11) DEFAULT NULL COMMENT '兴趣爱好id',
  `comment_id` int(11) DEFAULT NULL COMMENT '被回复的消息内容id',
  `type` tinyint(4) DEFAULT NULL COMMENT '类型，1系统消息、2用户回复@、3用户点赞、4通知、5私信回复、6删除评论',
  `create_time` int(11) unsigned DEFAULT NULL COMMENT '创建时间',
  `create_user` varchar(100) DEFAULT NULL COMMENT '0为系统，发件人ID',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态,-1删除，0未读，1已读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统消息表';

-- ----------------------------
-- Records of pb_message
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_model`
-- ----------------------------
DROP TABLE IF EXISTS `pb_model`;
CREATE TABLE `pb_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型标识',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '继承的模型',
  `relation` varchar(30) NOT NULL DEFAULT '' COMMENT '继承与被继承模型的关联字段',
  `need_pk` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '新建表时是否需要主键字段',
  `field_sort` text COMMENT '表单字段排序',
  `field_group` varchar(255) NOT NULL DEFAULT '1:基础' COMMENT '字段分组',
  `attribute_list` text COMMENT '属性列表（表的字段）',
  `attribute_alias` varchar(255) NOT NULL DEFAULT '' COMMENT '属性别名定义',
  `template_list` varchar(100) NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_add` varchar(100) NOT NULL DEFAULT '' COMMENT '新增模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑模板',
  `list_grid` text COMMENT '列表定义',
  `list_row` smallint(2) unsigned NOT NULL DEFAULT '10' COMMENT '列表数据长度',
  `search_key` varchar(50) NOT NULL DEFAULT '' COMMENT '默认搜索字段',
  `search_list` varchar(255) NOT NULL DEFAULT '' COMMENT '高级搜索的字段',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `engine_type` varchar(25) NOT NULL DEFAULT 'MyISAM' COMMENT '数据库引擎',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='文档模型表';

-- ----------------------------
-- Records of pb_model
-- ----------------------------
INSERT INTO `pb_model` VALUES ('1', 'document', '基础文档', '0', '', '1', '{\"1\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\",\"14\",\"15\",\"16\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\"]}', '1:基础', '', '', '', '', '', 'id:编号\r\ntitle:标题:[EDIT]\r\ntype:类型\r\nupdate_time:最后更新\r\nstatus:状态\r\nview:浏览\r\nid:操作:[EDIT]|编辑,[DELETE]|删除', '0', '', '', '1383891233', '1384507827', '1', 'MyISAM');
INSERT INTO `pb_model` VALUES ('2', 'article', '文章', '1', '', '1', '{\"1\":[\"3\",\"24\",\"2\",\"5\"],\"2\":[\"9\",\"13\",\"19\",\"10\",\"12\",\"16\",\"17\",\"26\",\"20\",\"14\",\"11\",\"25\"]}', '1:基础,2:扩展', '', '', '', '', '', '', '0', '', '', '1383891243', '1387260622', '1', 'MyISAM');
INSERT INTO `pb_model` VALUES ('3', 'download', '下载', '1', '', '1', '{\"1\":[\"3\",\"28\",\"30\",\"32\",\"2\",\"5\",\"31\"],\"2\":[\"13\",\"10\",\"27\",\"9\",\"12\",\"16\",\"17\",\"19\",\"11\",\"20\",\"14\",\"29\"]}', '1:基础,2:扩展', '', '', '', '', '', '', '0', '', '', '1383891252', '1387260449', '1', 'MyISAM');

-- ----------------------------
-- Table structure for `pb_picture`
-- ----------------------------
DROP TABLE IF EXISTS `pb_picture`;
CREATE TABLE `pb_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图片表';

-- ----------------------------
-- Records of pb_picture
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_push`
-- ----------------------------
DROP TABLE IF EXISTS `pb_push`;
CREATE TABLE `pb_push` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class` tinyint(4) NOT NULL COMMENT '表类型：1最新动态 2中心工作 3最多跑一次 4廉政文化 5执行力建设 ',
  `focus_main` int(11) unsigned NOT NULL COMMENT '主图文id',
  `focus_vice` varchar(255) DEFAULT '' COMMENT '副图文id',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `create_user` varchar(255) NOT NULL DEFAULT '' COMMENT '创建人',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 -1未通过0未审核1已发布',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='后台消息推送表';

-- ----------------------------
-- Records of pb_push
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_push_review`
-- ----------------------------
DROP TABLE IF EXISTS `pb_push_review`;
CREATE TABLE `pb_push_review` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `push_id` int(11) unsigned NOT NULL COMMENT '推送消息id',
  `user_id` varchar(255) NOT NULL DEFAULT '' COMMENT '用户id',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `review_time` int(11) unsigned NOT NULL COMMENT '审核时间',
  `status` tinyint(4) NOT NULL COMMENT '状态 -1 审核不通过 1 审核通过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='后台消息推送审核表';

-- ----------------------------
-- Records of pb_push_review
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_ucenter_admin`
-- ----------------------------
DROP TABLE IF EXISTS `pb_ucenter_admin`;
CREATE TABLE `pb_ucenter_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of pb_ucenter_admin
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_ucenter_app`
-- ----------------------------
DROP TABLE IF EXISTS `pb_ucenter_app`;
CREATE TABLE `pb_ucenter_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用ID',
  `title` varchar(30) NOT NULL COMMENT '应用名称',
  `url` varchar(100) NOT NULL COMMENT '应用URL',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT '应用IP',
  `auth_key` varchar(100) NOT NULL DEFAULT '' COMMENT '加密KEY',
  `sys_login` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '同步登陆',
  `allow_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '允许访问的IP',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应用表';

-- ----------------------------
-- Records of pb_ucenter_app
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_ucenter_member`
-- ----------------------------
DROP TABLE IF EXISTS `pb_ucenter_member`;
CREATE TABLE `pb_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` varchar(100) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '用户手机',
  `login` int(11) DEFAULT '0' COMMENT '登入次数',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of pb_ucenter_member
-- ----------------------------
INSERT INTO `pb_ucenter_member` VALUES ('1', 'admin', '02571bc54a0a4c08451a187d1929b208', '395936625@qq.com', '', '0', '1459647942', '2130706433', '1464224874', '3232235828', '1464224874', '1');

-- ----------------------------
-- Table structure for `pb_ucenter_setting`
-- ----------------------------
DROP TABLE IF EXISTS `pb_ucenter_setting`;
CREATE TABLE `pb_ucenter_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（1-用户配置）',
  `value` text NOT NULL COMMENT '配置数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置表';

-- ----------------------------
-- Records of pb_ucenter_setting
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_url`
-- ----------------------------
DROP TABLE IF EXISTS `pb_url`;
CREATE TABLE `pb_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_url` (`url`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='链接表';

-- ----------------------------
-- Records of pb_url
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_userdata`
-- ----------------------------
DROP TABLE IF EXISTS `pb_userdata`;
CREATE TABLE `pb_userdata` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型标识',
  `target_id` int(10) unsigned NOT NULL COMMENT '目标id',
  UNIQUE KEY `uid` (`uid`,`type`,`target_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pb_userdata
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_wechat_department`
-- ----------------------------
DROP TABLE IF EXISTS `pb_wechat_department`;
CREATE TABLE `pb_wechat_department` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(11) DEFAULT '1' COMMENT '父亲部门id。根部门id为1',
  `name` varchar(64) DEFAULT NULL COMMENT '部门名称。长度限制为1~64个字节，字符不能包括\\:*?"<>｜',
  `order` int(11) DEFAULT '0' COMMENT '在父部门中的次序值。order值小的排序靠前。',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='微信部门表';

-- ----------------------------
-- Records of pb_wechat_department
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_wechat_department_user`
-- ----------------------------
DROP TABLE IF EXISTS `pb_wechat_department_user`;
CREATE TABLE `pb_wechat_department_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `departmentid` int(11) unsigned DEFAULT NULL COMMENT '部门ID',
  `userid` varchar(100) DEFAULT NULL COMMENT '用户ID',
  `order` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='部门用户表';

-- ----------------------------
-- Records of pb_wechat_department_user
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_wechat_log`
-- ----------------------------
DROP TABLE IF EXISTS `pb_wechat_log`;
CREATE TABLE `pb_wechat_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(64) DEFAULT NULL COMMENT '成员UserID',
  `msgtype` varchar(100) DEFAULT NULL COMMENT '消息类型',
  `event` varchar(100) DEFAULT NULL COMMENT '事件类型，进入应用enter_agent',
  `create_time` int(11) unsigned DEFAULT NULL COMMENT '创建时间戳（1408091189）',
  `agentid` smallint(6) DEFAULT NULL COMMENT '具体应用ID',
  `event_key` varchar(100) DEFAULT NULL COMMENT '事件KEY值，暂时空',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of pb_wechat_log
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_wechat_tag`
-- ----------------------------
DROP TABLE IF EXISTS `pb_wechat_tag`;
CREATE TABLE `pb_wechat_tag` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` smallint(6) DEFAULT NULL,
  `tagname` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of pb_wechat_tag
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_wechat_user`
-- ----------------------------
DROP TABLE IF EXISTS `pb_wechat_user`;
CREATE TABLE `pb_wechat_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(64) NOT NULL COMMENT '成员UserID。对应管理端的帐号，企业内必须唯一。',
  `name` varchar(64) NOT NULL COMMENT '成员名称',
  `nickname` varchar(64) DEFAULT NULL COMMENT '昵称',
  `header` varchar(255) DEFAULT NULL COMMENT '头像',
  `department` tinyint(4) DEFAULT NULL COMMENT '成员所属部门id列表',
  `position` varchar(64) DEFAULT NULL COMMENT '职位信息',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号码',
  `gender` tinyint(4) DEFAULT '0' COMMENT '性别。0未定义、1男性、2女性',
  `email` varchar(64) DEFAULT NULL COMMENT '邮箱。企业内必须唯一',
  `weixinid` varchar(100) DEFAULT NULL COMMENT '微信号。企业内必须唯一。',
  `enable` tinyint(4) DEFAULT NULL COMMENT '启用/禁用成员',
  `avatar` varchar(500) DEFAULT NULL COMMENT '头像url。注：如果要获取小图将url最后的"/0"改成"/64"即可',
  `avatar_mediaid` varchar(500) DEFAULT NULL COMMENT '成员头像的mediaid',
  `extattr` varchar(500) DEFAULT NULL COMMENT 'WEB管理添加的扩展属性',
  `isleader` varchar(50) DEFAULT NULL,
  `hide_mobile` varchar(100) DEFAULT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  `english_name` varchar(100) DEFAULT NULL,
  `nation` varchar(50) DEFAULT NULL COMMENT '民族',
  `birthday` varchar(100) DEFAULT NULL COMMENT '出生日期',
  `education` varchar(50) DEFAULT NULL COMMENT '学历',
  `partytime` varchar(50) DEFAULT NULL COMMENT '入党时间',
  `branch` varchar(100) DEFAULT NULL COMMENT '所在支部',
  `worktime` varchar(100) DEFAULT NULL COMMENT '参加工作时间',
  `virtualnet` varchar(50) DEFAULT NULL COMMENT '虚拟网',
  `status` tinyint(4) DEFAULT NULL COMMENT '关注状态: 1已关注、2已冻结、4未关注',
  `score` int(11) DEFAULT '0' COMMENT '个人累计积分,浏览1分，点赞2分，评论3分',
  `trad_score` int(11) DEFAULT '0' COMMENT '抄党章传统模式得分，1章1分',
  `times` int(11) DEFAULT '0' COMMENT '传统模式抄写次数',
  `game_score` int(11) DEFAULT '0' COMMENT '游戏模式得分',
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='微信用户表';

-- ----------------------------
-- Records of pb_wechat_user
-- ----------------------------

-- ----------------------------
-- Table structure for `pb_wechat_user_tag`
-- ----------------------------
DROP TABLE IF EXISTS `pb_wechat_user_tag`;
CREATE TABLE `pb_wechat_user_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` smallint(11) DEFAULT NULL,
  `userid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of pb_wechat_user_tag
-- ----------------------------
