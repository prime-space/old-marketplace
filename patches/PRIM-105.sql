CREATE TABLE `metric` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `metricTypeId` TINYINT(3) UNSIGNED NOT NULL,
  `relateId` INT(10) UNSIGNED NOT NULL,
  `ip` VARCHAR(15) NOT NULL,
  `userAgent` VARCHAR(250) NOT NULL,
  `createdTs` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `relateId_metricTypeId` (`relateId`, `metricTypeId`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
