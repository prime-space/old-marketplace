ALTER TABLE `order`
  ADD COLUMN `payment_account_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' AFTER `pay_method_id`,
  CHANGE COLUMN `userGuest` `userGuest` INT(1) NOT NULL DEFAULT '0' COMMENT '0guest1user' AFTER `user_id`,
  CHANGE COLUMN `status` `status` VARCHAR(8) NOT NULL DEFAULT 'pay' AFTER `curr`,
  CHANGE COLUMN `date` `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `status`,
  CHANGE COLUMN `promocode_el_id_issued` `promocode_el_id_issued` INT(11) NOT NULL DEFAULT '0' AFTER `customer_purse`,
  CHANGE COLUMN `rating` `rating` DECIMAL(16,2) NOT NULL DEFAULT '0' AFTER `time_money_in`,
  ADD INDEX `payment_account_id` (`payment_account_id`);

CREATE TABLE `payment_system` (
  `id` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `payment_account` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `paymentSystemId` TINYINT(3) UNSIGNED NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `config` TEXT NOT NULL,
  `weight` TINYINT(3) UNSIGNED NOT NULL,
  `enabled` SET('shop','merchant','withdraw') NOT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;
