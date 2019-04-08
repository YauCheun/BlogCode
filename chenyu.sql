/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 80013
Source Host           : localhost:3306
Source Database       : chenyu

Target Server Type    : MYSQL
Target Server Version : 80013
File Encoding         : 65001

Date: 2019-04-08 22:15:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `categories`
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `slug` varchar(200) NOT NULL COMMENT '别名',
  `name` varchar(200) NOT NULL COMMENT '分类名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('1', 'uncategorized', ' 未分类   ');
INSERT INTO `categories` VALUES ('2', 'js', 'javascript');
INSERT INTO `categories` VALUES ('3', 'php', 'php ');
INSERT INTO `categories` VALUES ('4', 'jq', 'jquery');

-- ----------------------------
-- Table structure for `comments`
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `author` varchar(100) NOT NULL COMMENT '作者',
  `created` datetime NOT NULL COMMENT '创建时间',
  `content` varchar(1000) NOT NULL COMMENT '内容',
  `status` varchar(20) NOT NULL COMMENT '状态（held/approved/rejected/trashed）',
  `post_id` int(11) NOT NULL COMMENT '文章 ID',
  `parent_id` int(11) DEFAULT NULL COMMENT '父级 ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=545 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comments
-- ----------------------------
INSERT INTO `comments` VALUES ('526', '21', '2019-12-12 16:31:00', '21', 'approved', '1', null);
INSERT INTO `comments` VALUES ('527', '21', '2019-12-12 16:31:00', '21', 'approved', '1', null);
INSERT INTO `comments` VALUES ('528', '21', '2019-12-12 16:31:00', '21', 'approved', '1', null);
INSERT INTO `comments` VALUES ('529', '二', '2019-03-27 10:37:25', '二', 'approved', '1', null);
INSERT INTO `comments` VALUES ('531', '友哥哥', '2019-03-27 10:47:03', '你好帅', 'approved', '1', null);
INSERT INTO `comments` VALUES ('532', '是是', '2019-03-27 10:49:01', '的是', 'approved', '1', null);
INSERT INTO `comments` VALUES ('533', '友哥哥', '2019-03-27 11:42:54', '6565', 'approved', '1', null);
INSERT INTO `comments` VALUES ('534', 'jkhjkj ', '2019-03-27 11:43:02', '6565', 'approved', '1', null);
INSERT INTO `comments` VALUES ('535', 'jkhjk', '2019-03-27 11:43:30', '6565', 'approved', '1', null);
INSERT INTO `comments` VALUES ('537', '友哥哥', '2019-03-28 22:36:45', 'ee ', 'approved', '367', null);
INSERT INTO `comments` VALUES ('538', 'hai', '2019-04-08 14:11:46', 'bbbb', 'approved', '370', null);
INSERT INTO `comments` VALUES ('539', '55', '2019-04-08 14:13:06', '858', 'approved', '369', null);
INSERT INTO `comments` VALUES ('540', 'xixii', '2019-04-08 14:56:56', 'xixii', 'approved', '370', null);
INSERT INTO `comments` VALUES ('542', 'went', '2019-04-08 14:58:00', '非常好！！！', 'approved', '367', null);
INSERT INTO `comments` VALUES ('543', 'df ', '2019-04-08 15:13:21', 'df d', 'approved', '369', null);
INSERT INTO `comments` VALUES ('544', '友哥哥', '2019-04-08 20:52:15', 'efdf', 'approved', '365', null);

-- ----------------------------
-- Table structure for `options`
-- ----------------------------
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `key` varchar(200) NOT NULL COMMENT '属性键',
  `value` text NOT NULL COMMENT '属性值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of options
-- ----------------------------
INSERT INTO `options` VALUES ('1', 'site_url', 'http://localhost');
INSERT INTO `options` VALUES ('2', 'site_logo', '/static/assets/img/logo.png');
INSERT INTO `options` VALUES ('3', 'site_name', 'Thoughts, stories and ideas.');
INSERT INTO `options` VALUES ('4', 'site_description', 'Thoughts, stories and ideas.');
INSERT INTO `options` VALUES ('5', 'site_keywords', '生活，博客，心情，技术');
INSERT INTO `options` VALUES ('6', 'site_footer', '<p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>');
INSERT INTO `options` VALUES ('7', 'comment_status', '1');
INSERT INTO `options` VALUES ('8', 'comment_reviewed', '1');
INSERT INTO `options` VALUES ('9', 'nav_menus', '[{\"icon\":\"fa fa-glass\",\"text\":\"奇趣事\",\"title\":\"奇趣事\",\"link\":\"/category/funny\"},{\"icon\":\"fa fa-phone\",\"text\":\"潮科技\",\"title\":\"潮科技\",\"link\":\"/category/tech\"},{\"icon\":\"fa fa-fire\",\"text\":\"会生活\",\"title\":\"会生活\",\"link\":\"/category/living\"},{\"icon\":\"fa fa-gift\",\"text\":\"美奇迹\",\"title\":\"美奇迹\",\"link\":\"/category/travel\"}]');
INSERT INTO `options` VALUES ('10', 'home_slides', '[{\"image\":\"/static/uploads/slide_1.jpg\",\"text\":\"轮播项一\",\"link\":\"https://zce.me\"},{\"image\":\"/static/uploads/slide_2.jpg\",\"text\":\"轮播项二\",\"link\":\"https://zce.me\"}]');

-- ----------------------------
-- Table structure for `posts`
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `slug` varchar(200) NOT NULL COMMENT '别名',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `created` datetime NOT NULL COMMENT '创建时间',
  `content` text COMMENT '内容',
  `views` int(11) NOT NULL DEFAULT '0' COMMENT '浏览数',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `status` varchar(20) NOT NULL COMMENT '状态（drafted/published/trashed）',
  `user_id` int(11) NOT NULL COMMENT '用户 ID',
  `category_id` int(11) NOT NULL COMMENT '分类 ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=371 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of posts
-- ----------------------------
INSERT INTO `posts` VALUES ('1', 'first', '世界你好', '2019-03-26 13:49:00', '**第一篇文章lllflkjcvnvi哦梵蒂冈覅偶多个咖啡馆的法律环境规划局 打飞机的改好**![](http://img4.imgtn.bdimg.com/it/u=2998106246,3936872202&fm=15&gp=0.jpg)', '38', '0', 'published', '1', '1');
INSERT INTO `posts` VALUES ('2', '2', '2', '2019-03-23 12:42:09', '2', '3', '0', 'published', '1', '4');
INSERT INTO `posts` VALUES ('3', 'we', 'wew ', '2019-03-27 11:05:23', 'we', '3', '0', 'published', '1', '1');
INSERT INTO `posts` VALUES ('361', 'ddd', 'ddd ', '2019-03-23 11:55:00', 'ddd', '24', '0', 'published', '1', '3');
INSERT INTO `posts` VALUES ('365', 'ere ', 'rewrrew ', '2019-03-23 11:54:00', 'er ', '7', '0', 'published', '1', '2');
INSERT INTO `posts` VALUES ('366', 'ere e ', 'ewr er', '2019-03-27 11:08:00', ' ewr ', '0', '0', 'drafted', '1', '2');
INSERT INTO `posts` VALUES ('367', 'sdf发', '而非发', '2019-03-28 12:09:00', '<p><strong>反倒是 发的&nbsp;&nbsp;</strong> 发的的地方</p><p><strong><img src=\"/static/uploads/20190328/1553742614583932.jpeg\" title=\"1553742614583932.jpeg\" alt=\"20140319211347_QYst5.jpeg\" width=\"239\" height=\"159\"/></strong><br/></p>', '21', '0', 'published', '1', '1');
INSERT INTO `posts` VALUES ('368', '地方方法 风光', '地方士大夫撒', '2019-03-28 11:22:00', '<p><img src=\"/static/uploads/20190328/1553743434320581.jpg\" title=\"1553743434320581.jpg\" alt=\"avatar.jpg\" width=\"175\" height=\"182\"/>发的的&nbsp;<img src=\"http://img.baidu.com/hi/jx2/j_0016.gif\"/></p>', '4', '0', 'published', '1', '1');
INSERT INTO `posts` VALUES ('369', '风光', '苟富贵', '2019-03-28 12:11:00', '<p><strong>dfgldfk l</strong><br/></p><p>fg fg z离开发过了发&nbsp; 规范非官方个地方梵蒂冈电饭锅的双方各的双方各是大法官是大法官是大法官水电费刚发的和发过火个感觉东方国际化<strong><br/></strong></p><p>个电饭锅风光&nbsp;<br/></p><p><img src=\"/static/uploads/20190328/1553745749295724.jpg\" title=\"1553745749295724.jpg\" alt=\"avatar.jpg\" width=\"192\" height=\"201\"/></p><p>fdg dfg&nbsp;</p><p><img src=\"http://chenyu.io/static/uploads/20190328/1553745749295724.jpg\" title=\"1553745749295724.jpg\" alt=\"avatar.jpg\" width=\"192\" height=\"201\"/></p><p>fdgsdfg 速达方法的个 是大法官</p><p>风光是大法官</p><p>风光</p><p><img src=\"http://chenyu.io/static/uploads/20190328/1553745749295724.jpg\" title=\"1553745749295724.jpg\" alt=\"avatar.jpg\" width=\"192\" height=\"201\"/></p><p><img src=\"http://chenyu.io/static/uploads/20190328/1553745749295724.jpg\" title=\"1553745749295724.jpg\" alt=\"avatar.jpg\" width=\"192\" height=\"201\"/></p><p><img src=\"http://chenyu.io/static/uploads/20190328/1553745749295724.jpg\" title=\"1553745749295724.jpg\" alt=\"avatar.jpg\" width=\"192\" height=\"201\"/></p><p><img src=\"http://chenyu.io/static/uploads/20190328/1553745749295724.jpg\" title=\"1553745749295724.jpg\" alt=\"avatar.jpg\" width=\"192\" height=\"201\"/></p><p><img src=\"http://chenyu.io/static/uploads/20190328/1553745749295724.jpg\" title=\"1553745749295724.jpg\" alt=\"avatar.jpg\" width=\"192\" height=\"201\"/></p><p>风光</p><p>&nbsp;g单方事故单方事故</p><p>电饭锅sdf sdfg反倒是g</p><p>发送到g水电费g</p><p><br/></p><p>反倒是</p><p>单方事故</p><p><br/></p><p><br/></p><p>水电费g讽德诵功sdfg富商大贾</p><p>单方事故单方事故</p><p><br/></p><p>水电费g</p><p>是大法官</p><p><br/></p><p>水电费g</p><p>水电费gdf个sdf&nbsp;</p><p>梵蒂冈电风扇</p>', '19', '0', 'published', '1', '1');
INSERT INTO `posts` VALUES ('370', '风光d ', '苟富贵d ', '2019-03-28 12:03:00', '<pre class=\"brush:groovy;toolbar:false\"><br/></pre><p><strong>dfgldfk l</strong><br/></p><p>fg fg z离开发过了发&nbsp; 规范非官方个地方梵蒂冈电饭锅的双方各的双方各是大法官是大法官是大法官水电费刚发的和发过火个感觉东方国际化<strong><br/></strong></p><p>个电饭锅风光&nbsp;<br/></p><p><img src=\"/static/uploads/20190328/1553745749295724.jpg\" title=\"1553745749295724.jpg\" alt=\"avatar.jpg\" width=\"192\" height=\"201\"/></p><pre class=\"brush:php;toolbar:false\">&lt;?&nbsp;php&nbsp;echo&nbsp;&quot;df&quot;?&gt;</pre><p><br/></p>', '11', '0', 'published', '1', '1');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `slug` varchar(200) NOT NULL COMMENT '别名',
  `email` varchar(200) NOT NULL COMMENT '邮箱',
  `password` varchar(200) NOT NULL COMMENT '密码',
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(200) DEFAULT NULL COMMENT '头像',
  `bio` varchar(500) DEFAULT NULL COMMENT '简介',
  `root` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '鐘舵€侊紙unactivated/activated/forbidden/trashed锛?',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'yaucheung@gmail.com', '49b0b8be74c0913863811392c32137a6', '张友', '/static/uploads/img-5c95f6928664b.jpeg', 'MAKE IT BETTER!', 'root');
