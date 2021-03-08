ALTER TABLE `mpayments`
  ADD COLUMN `payment_account_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' AFTER `yandex_cart_context_id`,
  ADD INDEX `payment_account_id` (`payment_account_id`);
ALTER TABLE `addmoney`
  ADD COLUMN `payment_account_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' AFTER `sum`,
  ADD INDEX `payment_account_id` (`payment_account_id`);
