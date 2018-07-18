CREATE TABLE `z_bkxqhx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hxid` varchar(50) not null DEFAULT '' COMMENT '户型id',
  `xqid` varchar(50) NOT NULL DEFAULT '' COMMENT '小区id',
  `title` varchar(255) not null DEFAULT '' comment'标题',
  `img` varchar(500) not null DEFAULT '' comment'图片',
  `price` varchar(11) DEFAULT NULL COMMENT '价格',
  `info` varchar(600) not null DEFAULT ''comment'户型信息',
  `detail` varchar(800) NOT NULL DEFAULT '' COMMENT '户型分间',
  `page` varchar(255) not null DEFAULT '' comment '分页url',
  `addtime` int(10) not null DEFAULT 0 comment'添加时间',
  PRIMARY KEY (`id`),
  KEY `hxid` (`hxid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;