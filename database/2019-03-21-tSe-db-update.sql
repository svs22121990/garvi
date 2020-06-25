ALTER TABLE `sales_type` ADD `default_status` ENUM('clear','pending') NOT NULL DEFAULT 'clear' AFTER `label`;

UPDATE `sales_type` SET `default_status` = 'pending' WHERE `sales_type`.`id` = 2;

ALTER TABLE `invoice` ADD `payment_status` ENUM('clear','pending') NOT NULL DEFAULT 'clear' AFTER `payment_mode`;

CREATE TABLE `admin_GST`.`invoice_settlements` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `invoice_id` INT(11) UNSIGNED NOT NULL , `date` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

