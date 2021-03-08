INSERT INTO `paymethod` (`id`, `name`, `url`, `pic`) VALUES (NULL, 'Skrill', 'https://www.skrill.com/ru/', 'panel/user/currencies/img/skrill.png');
ALTER TABLE `pay_method` ADD `fee` DECIMAL(4,2) NULL AFTER `info`;
INSERT INTO `mpayagg` (`id`, `name`, `config`) VALUES ('18', 'skrill', '');
ALTER TABLE `mpaysyss` ADD `code` VARCHAR(16) NULL AFTER `enabled`;

-- methods for shop
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (18, 18, '3', 'Skrill', 'WLT', 'skrill', 'skrill', 'Поддерживается для всех стран', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (19, 19, '3', 'Neteller', 'NTL', 'skrill', 'neteller', 'Поддерживается для всех стран', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (20, 20, '3', 'Paysafecard', 'PSC', 'skrill', 'paysafecard', 'Поддерживаемые страны: American Samoa, Austria, Belgium, Canada, Croatia, Cyprus, Czech Republic, Denmark, Finland, France, Germany, Guam, Hungary, Ireland, Italy, Latvia, Luxembourg, Malta, Mexico, Netherlands, Northern Mariana Islands, Norway, Poland, Portugal, Puerto Rico, Romania, Slovakia, Slovenia, Spain, Sweden, Switzerland, Turkey, United Kingdom, United States Of America and US Virgin Islands', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (21, 21, '3', 'Resurs', 'RSB', 'skrill', 'resurs', 'Поддерживаемые страны: Sweden, Norway, Finland and Denmark', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (22, 22, '3', 'Credit', 'ACC', 'skrill', 'credit', 'Поддерживается для всех стран', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (23, 23, '3', 'Rapid Transfer', 'OBT', 'skrill', 'rapid', 'Поддерживаемые страны: Austria, Denmark, Finland, France, Germany, Hungary, Italy, Norway, Poland, Portugal, Spain, Sweden, United Kingdom.', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (24, 24, '3', 'Giropay', 'GIR', 'skrill', 'giropay', 'Поддерживаемые страны: Germany.', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (25, 25, '3', 'Direct Debit/SEPA', 'DID', 'skrill', 'sepa', 'Поддерживаемые страны: Germany', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (26, 26, '3', 'Sofort (Sofortueberweisung) ', 'SFT', 'skrill', 'sofort', 'Поддерживаемые страны: Germany, Austria, Belgium, Netherlands, Italy, France, Poland, Hungary, Slovakia, Czech', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (27, 27, '3', 'Nordea Solo', 'EBT', 'skrill', 'nordea', 'Поддерживаемые страны: Sweden', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (28, 28, '3', 'iDEAL', 'IDL', 'skrill', 'ideal', 'Поддерживаемые страны: Netherlands', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (29, 29, '3', 'EPS (Netpay)', 'NPY', 'skrill', 'netpay', 'Поддерживаемые страны: Austria', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (30, 30, '3', 'POLi', 'PLI', 'skrill', 'poly', 'Поддерживаемые страны: Australia', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (31, 31, '3', 'Przelewy24', 'PWY', 'skrill', 'przelewy', 'Поддерживаемые страны: Poland', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (32, 32, '3', 'Trustly', 'EPY', 'skrill', 'trustly', 'Поддерживаемые страны: Austria, Belgium, Bulgaria, Czech Republic, Denmark, Estonia, Finland, Germany, Hungary, Ireland, Latvia, Lithuania, Netherlands, Poland, Romania, Slovakia, Slovenia, Spain, Sweden.', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (33, 33, '3', 'ePay.bg', 'GLU', 'skrill', 'ePay', 'Поддерживаемые страны: Bulgaria', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (34, 34, '3', 'Alipay', 'ALI', 'skrill', 'alipay', 'Поддерживаемые страны: China', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (35, 35, '3', 'Unionpay (via Astropay)', 'AUP', 'skrill', 'unionpay', 'Поддерживаемые страны: China', 10);
INSERT INTO `pay_method` (`id`, `relate`, `curr`, `name`, `code`, `system`, `pic`, `info`, `fee`) VALUES (36, 36, '3', 'Skrill Bitcoin', 'BTC', 'skrill', 'bitcoin', 'Поддерживается для всех стран', 10);

-- methods for merchant
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('19', '18', 'Skrill', 10, 'skrill', b'1', b'1', 'WLT');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('20', '18', 'Neteller', 10, 'skrill', b'1', b'1', 'NTL');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('21', '18', 'Paysafecard', 10, 'skrill', b'1', b'1', 'PSC');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('22', '18', 'Resurs', 10, 'skrill', b'1', b'1', 'RSB');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('23', '18', 'Credit', 10, 'skrill', b'1', b'1', 'ACC');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('24', '18', 'Rapid Transfer', 10, 'skrill',b'1', b'1', 'OBT');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('25', '18', 'Giropay', 10, 'skrill', b'1', b'1', 'GIR');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('26', '18', 'Direct Debit/SEPA', 10, 'skrill', b'1', b'1', 'DID');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('27', '18', 'Sofort (Sofortueberweisung)', 10, 'skrill', b'1', b'1', 'SFT');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('28', '18', 'Nordea Solo', 10, 'skrill', b'1', b'1', 'EBT');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('29', '18', 'iDEAL', 10 ,  'skrill', b'1', b'1', 'IDL');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('30', '18', 'EPS (Netpay)', 10, 'skrill', b'1', b'1', 'NPY');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('31', '18', 'POLi', 10, 'skrill', b'1', b'1', 'PLI');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('32', '18', 'Przelewy25', 10, 'skrill', b'1', b'1', 'PWY');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('33', '18', 'Trustly', 10, 'skrill', b'1', b'1', 'EPY');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('34', '18', 'ePay.bg', 10, 'skrill',b'1', b'1', 'GLU');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('35', '18', 'Alipay', 10, 'skrill',b'1', b'1', 'ALI');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('36', '18', 'Unionpay (via Astropay)', 10, 'skrill',b'1', b'1', 'AUP');
INSERT INTO `mpaysyss` (`id`, `aggId`, `name`, `fee`, `via`, `fortest`, `enabled`, `code`) VALUES ('37', '18', 'Skrill Bitcoin', 10, 'skrill',b'1', b'1', 'BTC');