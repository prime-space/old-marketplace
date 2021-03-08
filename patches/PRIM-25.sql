INSERT INTO `setting` (`id`, `ids`, `name`, `value`, `date`) VALUES ('13', '13', 'yandex_auth_token', 'temp', NULL);
INSERT INTO `setting` (`id`, `ids`, `name`, `value`, `date`) VALUES ('14', '14', 'yandex_auth_token_changed', '0', NULL);
INSERT INTO `setting` (`id`, `ids`, `name`, `value`, `date`) VALUES ('15', '15', 'yandex_autopayments', '1', NULL), ('16', '16', 'yandex_autopayments_fee', '1.5', NULL);

CREATE TABLE `primearea`.`yandex_autopayments` ( 
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT , 
	`cashout_id` INT(10) NOT NULL , 
	`amount` DECIMAL(16,2) NOT NULL , 
	`comm` DECIMAL(16,2) NOT NULL , 
	`pursedest` VARCHAR(64) NOT NULL COMMENT 'Beneficiary account', 
	`result` VARCHAR(13) NOT NULL COMMENT 'operation result', 
	`datetime_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
	`datetime_execute` TIMESTAMP NULL DEFAULT NULL, 
	`protect` VARCHAR(13) NULL COMMENT 'protection code',  
	PRIMARY KEY (`id`), INDEX `cashout_id` (`cashout_id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;