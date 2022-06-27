CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_group` (
  `id_group` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `bg_repeat` varchar(50) NOT NULL,
  `bg_position` varchar(255) NOT NULL,
  `bg_fixed` varchar(50) NOT NULL,
  `bg_color` varchar(30) NOT NULL,
  `overlay` varchar(30) NOT NULL,
  `delay` int(10) unsigned NOT NULL,
  `activation` tinyint(3) unsigned NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `default_group` tinyint(3) unsigned NOT NULL,
  `usertype` tinyint(3) unsigned NOT NULL COMMENT '0=>all; 1=> visitor ; 2=> custumer',
  PRIMARY KEY (`id_group`)
) DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idgroup_idcat` (
  `id_group` int(11) NOT NULL,
  `id_cat` int(11) NOT NULL,
  PRIMARY KEY (`id_group`,`id_cat`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idgroup_idprodcat` (
  `id_group` int(11) NOT NULL,
  `id_cat` int(11) NOT NULL,
  PRIMARY KEY (`id_group`,`id_cat`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idgroup_idcms` (
  `id_group` int(11) NOT NULL,
  `id_cms` int(11) NOT NULL,
  PRIMARY KEY (`id_group`,`id_cms`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idgroup_idman` (
  `id_group` int(11) NOT NULL,
  `id_manufacturer` int(11) NOT NULL,
  PRIMARY KEY (`id_group`,`id_manufacturer`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idgroup_idproduct` (
  `id_group` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  PRIMARY KEY (`id_group`,`id_product`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idgroup_idslide` (
  `id_sort` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_group` int(11) unsigned NOT NULL,
  `id_slide` int(11) unsigned NOT NULL,
  `sort` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_group`,`id_slide`),
  KEY `id_sort` (`id_sort`)
) DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idgroup_idsupp` (
  `id_group` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  PRIMARY KEY (`id_group`,`id_supplier`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idgroup_page` (
  `id_group` int(11) NOT NULL,
  `page` varchar(255) NOT NULL,
  PRIMARY KEY (`id_group`,`page`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_slide` (
  `id_slide` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `bg_halign` tinyint(4) NOT NULL,
  `bg_valign` tinyint(4) NOT NULL,
  `bg_color` varchar(20) NOT NULL,
  `bg_position` varchar(255) NOT NULL,
  `bg_repeat` varchar(20) NOT NULL,
  `bg_fixed` varchar(20) NOT NULL,
  `fade_time` int(10) unsigned NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `activation` tinyint(10) unsigned NOT NULL,
  `bg_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id_slide`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_slide_lang` (
  `id_slide` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `image` varchar(32) NOT NULL,
  PRIMARY KEY (`id_slide`,`id_lang`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idrule_idgroup` (
  `id_rule` int(10) unsigned NOT NULL COMMENT 'Correspond � l''id group dans les autres tables',
  `id_group` int(10) unsigned NOT NULL COMMENT 'group de users',
  PRIMARY KEY (`id_rule`,`id_group`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idrule_idczone` (
  `id_rule` int(10) unsigned NOT NULL,
  `id_czone` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_rule`,`id_czone`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_idslide_idczone` (
  `id_slide` int(10) unsigned NOT NULL,
  `id_czone` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_slide`,`id_czone`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_clickzone_lang` (
  `id_czone` int(10) unsigned NOT NULL,
  `id_lang` smallint(5) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  PRIMARY KEY (`id_czone`,`id_lang`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_pm_advanced_bg_clickzone` (
  `id_czone` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL,
  `position` tinyint(3) unsigned NOT NULL COMMENT '1-> fixed ; 2 -> scroll',
  `width` varchar(10) NOT NULL,
  `height` varchar(10) NOT NULL,
  `marginLeft` smallint(5) unsigned NOT NULL,
  `marginTop` smallint(5) unsigned NOT NULL,
  `border` tinyint(3) unsigned NOT NULL,
  `color` varchar(8) NOT NULL,
  `side` tinyint(3) unsigned NOT NULL COMMENT '1=> left , 2=>right',
  PRIMARY KEY (`id_czone`)
) DEFAULT CHARSET=utf8 ;