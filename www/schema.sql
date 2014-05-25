CREATE TABLE IF NOT EXISTS `speedtest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` int(11) NOT NULL,
  `ping` float NOT NULL,
  `dl` float NOT NULL,
  `ul` float NOT NULL,
  `server_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;
