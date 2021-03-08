ALTER TABLE `review`
  ADD COLUMN `userId` INT NOT NULL AFTER `datedel`,
  ADD INDEX `userId` (`userId`);

UPDATE review r
  JOIN product p ON p.id = r.idProduct
SET r.userId = p.idUser;
