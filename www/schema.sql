CREATE TABLE IF NOT EXISTS `speedtest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` int(11) NOT NULL,
  `ping` float NOT NULL,
  `dl` float NOT NULL,
  `ul` float NOT NULL,
  `server_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ts` (`ts`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `system` (
  `config` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  UNIQUE KEY `config` (`config`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `system` (`config`, `value`) VALUES
('last_low_speed_notification', '0'),
('last_no_submission_notification', '0');
