INSERT INTO `paymethod`
(`id`, `name`, `url`, `pic`)
VALUES
  (NULL, 'QiwiOwn', 'https://www.qiwi.ru/', 'panel/user/currencies/img/qiwi.png');

ALTER TABLE `pay_method`
  ADD COLUMN `position` INT(11) NOT NULL DEFAULT '0' AFTER `fee`,
  ADD COLUMN `enabled` BIT NOT NULL DEFAULT b'1' AFTER `position`;

UPDATE `pay_method` SET `position` = 10 WHERE id = 1;
UPDATE `pay_method` SET `position` = 15 WHERE id = 2;
UPDATE `pay_method` SET `position` = 20 WHERE id = 3;
UPDATE `pay_method` SET `position` = 25 WHERE id = 4;
UPDATE `pay_method` SET `position` = 30 WHERE id = 5;
UPDATE `pay_method` SET `position` = 35, `enabled` = 0 WHERE id = 6;
UPDATE `pay_method` SET `position` = 40 WHERE id = 7;
UPDATE `pay_method` SET `position` = 45 WHERE id = 8;
UPDATE `pay_method` SET `position` = 50 WHERE id = 9;
UPDATE `pay_method` SET `position` = 55 WHERE id = 10;
UPDATE `pay_method` SET `position` = 60 WHERE id = 11;
UPDATE `pay_method` SET `position` = 65 WHERE id = 12;
UPDATE `pay_method` SET `position` = 70 WHERE id = 13;
UPDATE `pay_method` SET `position` = 75 WHERE id = 14;
UPDATE `pay_method` SET `position` = 80 WHERE id = 15;
UPDATE `pay_method` SET `position` = 85, `code` = 'other' WHERE id = 17;
UPDATE `pay_method` SET `position` = 90 WHERE id = 18;
UPDATE `pay_method` SET `position` = 95 WHERE id = 19;
UPDATE `pay_method` SET `position` = 100 WHERE id = 20;
UPDATE `pay_method` SET `position` = 105 WHERE id = 22;
UPDATE `pay_method` SET `position` = 110 WHERE id = 23;
UPDATE `pay_method` SET `position` = 115 WHERE id = 26;
UPDATE `pay_method` SET `position` = 120 WHERE id = 32;
UPDATE `pay_method` SET `position` = 125 WHERE id = 34;
UPDATE `pay_method` SET `position` = 130 WHERE id = 35;
UPDATE `pay_method` SET `position` = 135 WHERE id = 36;

UPDATE `pay_method` SET `fee` = 0 WHERE `fee` IS NULL;

ALTER TABLE `pay_method`
  CHANGE COLUMN `fee` `fee` DECIMAL(4,2) NOT NULL DEFAULT '0' AFTER `info`;

ALTER TABLE `pay_method`
  ALTER `code` DROP DEFAULT;
ALTER TABLE `pay_method`
  CHANGE COLUMN `code` `code` VARCHAR(64) NOT NULL AFTER `name`;

INSERT INTO `pay_method`
(`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`, `position`, `enabled`)
VALUES
  ('37', '37', '4', 'QIWI', 'qiwi', 'qiwi', 'qiwi641',
   '<a href=http://qiwi.ru/private/qiwiwallet/ target=_blank>Что такое QIWI-кошелек?</a><br><a href=https://visa.qiwi.com/replenish/main.action target=_blank>Как пополнить свой QIWI-кошелек?</a><br><br>Через платёжный сервис Robokassa на ваш номер в системе QIWI будет выписан счёт, который будет необходимо оплатить в Личном Кабинете QIWI. Если в процессе оплаты у вас возникнут затруднения, просьба обращаться в <a href=http://www.robokassa.ru/ru/Support/SendMsg.aspx target=_blank>службу технической поддержки</a> данного сервиса.',
   9.00, 35, b'1');


INSERT INTO `mpayagg`
  (`id`, `name`, `config`)
VALUES
  ('19', 'qiwiown', '');

ALTER TABLE `mpaysyss`
  ADD COLUMN `visible` BIT NOT NULL DEFAULT b'1' AFTER `code`,
  ADD COLUMN `position` INT(11) NOT NULL DEFAULT '0' AFTER `visible`;


INSERT INTO `mpaysyss`
  (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`, `visible`, `position`)
VALUES
  ('41', '19', 'QIWI', '9', 'qiwi', b'1', b'1', NULL, b'1', 45);

UPDATE `mpaysyss` SET `position` = 10 WHERE id = 1;
UPDATE `mpaysyss` SET `position` = 15 WHERE id = 2;
UPDATE `mpaysyss` SET `position` = 20 WHERE id = 3;
UPDATE `mpaysyss` SET `position` = 25 WHERE id = 4;
UPDATE `mpaysyss` SET `position` = 30 WHERE id = 5;
UPDATE `mpaysyss` SET `position` = 35 WHERE id = 6;
UPDATE `mpaysyss` SET `position` = 40 WHERE id = 7;
UPDATE `mpaysyss` SET `position` = 45, `visible` = 0, `enabled` = 0 WHERE id = 8;
UPDATE `mpaysyss` SET `position` = 50 WHERE id = 9;
UPDATE `mpaysyss` SET `position` = 55 WHERE id = 10;
UPDATE `mpaysyss` SET `position` = 60 WHERE id = 11;
UPDATE `mpaysyss` SET `position` = 65 WHERE id = 12;
UPDATE `mpaysyss` SET `position` = 70 WHERE id = 13;
UPDATE `mpaysyss` SET `position` = 75 WHERE id = 14;
UPDATE `mpaysyss` SET `position` = 80 WHERE id = 15;
UPDATE `mpaysyss` SET `position` = 85 WHERE id = 16;
UPDATE `mpaysyss` SET `position` = 90 WHERE id = 17;
UPDATE `mpaysyss` SET `position` = 95 WHERE id = 18;
UPDATE `mpaysyss` SET `position` = 100 WHERE id = 19;
UPDATE `mpaysyss` SET `position` = 105 WHERE id = 20;
UPDATE `mpaysyss` SET `position` = 110 WHERE id = 21;
UPDATE `mpaysyss` SET `position` = 115 WHERE id = 22;
UPDATE `mpaysyss` SET `position` = 120 WHERE id = 23;
UPDATE `mpaysyss` SET `position` = 125 WHERE id = 24;
UPDATE `mpaysyss` SET `position` = 130 WHERE id = 25;
UPDATE `mpaysyss` SET `position` = 135 WHERE id = 26;
UPDATE `mpaysyss` SET `position` = 140 WHERE id = 27;
UPDATE `mpaysyss` SET `position` = 145 WHERE id = 28;
UPDATE `mpaysyss` SET `position` = 150 WHERE id = 29;
UPDATE `mpaysyss` SET `position` = 155 WHERE id = 30;
UPDATE `mpaysyss` SET `position` = 160 WHERE id = 31;
UPDATE `mpaysyss` SET `position` = 165 WHERE id = 32;
UPDATE `mpaysyss` SET `position` = 170 WHERE id = 33;
UPDATE `mpaysyss` SET `position` = 175 WHERE id = 34;
UPDATE `mpaysyss` SET `position` = 180 WHERE id = 35;
UPDATE `mpaysyss` SET `position` = 185 WHERE id = 36;
UPDATE `mpaysyss` SET `position` = 190 WHERE id = 37;

CREATE TABLE `qiwi_input` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `txnId` BIGINT(20) UNSIGNED NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `transaction_type` VARCHAR(50) NULL DEFAULT NULL,
  `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `is_handled` BIT(1) NOT NULL,
  `created_ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;
ALTER TABLE `qiwi_input`
  ADD UNIQUE INDEX `transaction_id_transation_type` (`transaction_id`, `transaction_type`);
ALTER TABLE `qiwi_input`
  CHANGE COLUMN `is_handled` `is_handled` BIT(1) NOT NULL DEFAULT b'0' AFTER `transaction_id`;

ALTER TABLE `mshops`
  CHANGE COLUMN `available_paysyss_user` `available_paysyss_user` VARCHAR(255) NULL DEFAULT NULL AFTER `paysyss_fee`;

UPDATE mshops
SET available_paysyss_user = CONCAT(available_paysyss_user, ',41')
WHERE
  available_paysyss_user IS NOT NULL
  AND available_paysyss_user <> '';

ALTER TABLE `mpayments`
  ADD INDEX `ts_viaId_status` (`ts`, `viaId`, `status`);

ALTER TABLE `order`
  ADD COLUMN `pay_method_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' AFTER `taken_yandex_sum`;

ALTER TABLE `order`
  DROP INDEX `date`,
  ADD INDEX `date_pay_method_id` (`date`, `pay_method_id`);

