ALTER TABLE `promocode_el` ADD `user_id` INT(10) UNSIGNED NOT NULL AFTER `comment`;
ALTER TABLE `promocode_el` DROP INDEX `code`, ADD UNIQUE `code_user_id` (`code`, `user_id`);