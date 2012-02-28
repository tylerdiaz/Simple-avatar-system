CREATE TABLE `avatar_items` (
  `item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `layer_id` int(11) DEFAULT NULL,
  `item_name` varchar(120) DEFAULT NULL,
  `image_path` varchar(120) DEFAULT NULL,
  `thumbnail` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `layer_id` (`layer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

CREATE TABLE `avatar_layers` (
  `layer_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `layer_order` int(6) unsigned NOT NULL,
  `layer_name` varchar(30) NOT NULL DEFAULT '',
  `permanent` int(6) unsigned NOT NULL,
  PRIMARY KEY (`layer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

CREATE TABLE `avatars` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(32) DEFAULT NULL,
  `avatar_data` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;