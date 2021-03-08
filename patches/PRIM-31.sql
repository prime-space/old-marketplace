CREATE TABLE `user_privileges_settings` ( 
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT , 
	`name` VARCHAR(32) NULL, 
	`alias` VARCHAR(32) NULL,  
	PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

INSERT INTO `user_privileges_settings` (`id`, `name`, `alias`) VALUES ('1', 'basic', 'Базовый');
INSERT INTO `user_privileges_settings` (`id`, `name`, `alias`) VALUES ('2', 'neutral', 'Нейтральный');
INSERT INTO `user_privileges_settings` (`id`, `name`, `alias`) VALUES ('3', 'independent', 'Независимый');
INSERT INTO `user_privileges_settings` (`id`, `name`, `alias`) VALUES ('4', 'profesional', 'Профессионал');