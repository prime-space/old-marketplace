-- paysyss_fee VARCHAR(264) NULL DEFAULT NULL
ALTER TABLE `mshops` CHANGE `paysyss_fee` `paysyss_fee` TEXT NULL DEFAULT NULL;

ALTER TABLE `mshops` ADD `paysyss_override` tinyint(1) unsigned NULL DEFAULT '0' AFTER `available_paysyss_user`;