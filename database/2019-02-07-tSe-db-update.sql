--
-- Table structure for table `sales_type`
--
 
 CREATE TABLE `sales_type` (
   `id` int(2) UNSIGNED NOT NULL,
   `label` varchar(100) COLLATE ascii_bin NOT NULL,
   `description` varchar(254) COLLATE ascii_bin DEFAULT NULL,
   `status` enum('Active','Inactive') COLLATE ascii_bin NOT NULL DEFAULT 'Inactive'
 ) ENGINE=InnoDB DEFAULT CHARSET=ascii COLLATE=ascii_bin;
 --
 -- Dumping data for table `sales_type`
 --
 
 INSERT INTO `sales_type` (`id`, `label`, `description`, `status`) VALUES
 (1, 'Cash Sales', NULL, 'Active'),
 (2, 'Credit Sales', NULL, 'Active'),
 (3, 'Uniform Sales', NULL, 'Active');
 
 -- --------------------------------------------------------
 
 -- Indexes for table `sales_type`
 --
 ALTER TABLE `sales_type`
   ADD PRIMARY KEY (`id`);
 
 --
 --
 -- AUTO_INCREMENT for table `sales_type`
 --
 ALTER TABLE `sales_type`
   MODIFY `id` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

 --
 --
 -- Add `sales_type` to `invoice`
 --
ALTER TABLE `invoice` ADD `invoice_sales_type` INT(2) NOT NULL AFTER `serial_no_of_invoice`;

ALTER TABLE `invoice` ADD `payment_mode` INT(2) NOT NULL AFTER `date_of_invoice`;

ALTER TABLE `assets` ADD `purchase_date` DATE NOT NULL AFTER `received_date`;

CREATE TABLE `admin_GST`.`product_type` ( `id` INT(3) UNSIGNED NOT NULL AUTO_INCREMENT , `label` VARCHAR(50) NOT NULL , `status` ENUM('Active','Inactive') NOT NULL DEFAULT 'Inactive' , PRIMARY KEY (`id`)) ENGINE = InnoDB;

INSERT INTO `product_type` (`id`, `label`, `status`) VALUES (NULL, 'Corporation', 'Active'), (NULL, 'Consignment', 'Active')

ALTER TABLE `assets` ADD `product_type_id` INT(3) NOT NULL AFTER `asset_type_id`;