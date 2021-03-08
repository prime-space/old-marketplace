UPDATE `promocode_el` prel 
JOIN `promocodes` pr ON prel.promocode_id = pr.id
SET prel.user_id = pr.user_id