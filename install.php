<?php
//SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
//SET time_zone = "+00:00";
	$query = 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE TABLE IF NOT EXISTS `mapping` (
  `key` varchar(255) CHARACTER SET latin1 NOT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

CREATE TABLE IF NOT EXISTS `urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=5 ;

ALTER TABLE `mapping`
  ADD CONSTRAINT `mapping_ibfk_1` FOREIGN KEY (`id`) REFERENCES `urls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;';
  
	$sqli = new mysqli("localhost", "nbaztec", "");
	if ($sqli->connect_error) 
		die('Connect Error ('.$sqli->connect_errno.') '.$sqli->connect_error);
	$sqli->select_db("test");
	$sqli->multi_query($query);
	if($sqli->errno === 0)
		echo "Tables successfully created.";
	else
		echo "Error creating tables. ".$sqli->error."[".$sqli->errno."]";
?>