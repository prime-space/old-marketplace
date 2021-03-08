ALTER TABLE `user`
  ADD COLUMN `googleSecret` VARCHAR(16) NOT NULL DEFAULT '' AFTER `emailInforming`;
