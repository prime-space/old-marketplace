CREATE TABLE `qiwi_output` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `cashout_id` INT(10) UNSIGNED NOT NULL,
    `amount` DECIMAL(16,2) UNSIGNED NOT NULL,
    `fee` DECIMAL(16,2) UNSIGNED NOT NULL,
    `purse` VARCHAR(50) NOT NULL,
    `created_ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `cashout_id` (`cashout_id`)
)
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
    AUTO_INCREMENT=1000
;

INSERT INTO `setting`
(`id`, `ids`, `name`, `value`, `date`)
    VALUES
(18, 18, 'qiwi_autopayments', '1', NULL),
(19, 19, 'qiwi_autopayments_fee', '5', NULL);
