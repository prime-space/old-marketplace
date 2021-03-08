ALTER TABLE `cashout`
  ADD COLUMN `externalId` BIGINT(20) UNSIGNED NULL DEFAULT NULL AFTER `paymentAccountId`;
ALTER TABLE `cashout`
  ADD COLUMN `debit` DECIMAL(16,2) NULL DEFAULT NULL AFTER `amount`;
ALTER TABLE `cashout`
  ADD UNIQUE INDEX `externalId` (`externalId`);
