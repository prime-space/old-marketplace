ALTER TABLE `product`
  ADD COLUMN `inStock` TINYINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'In stock (bool)' AFTER `rating`;
