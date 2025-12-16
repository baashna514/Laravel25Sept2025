-- Create transactions table for Easypaisa payment integration
-- Run this SQL manually in your database

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) NOT NULL,
  `order_ref_num` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','success','failed','cancelled') NOT NULL DEFAULT 'pending',
  `easypaisa_transaction_id` varchar(255) DEFAULT NULL,
  `response_code` varchar(10) DEFAULT NULL,
  `callback_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_transaction_id_unique` (`transaction_id`),
  KEY `transactions_order_ref_num_index` (`order_ref_num`),
  KEY `transactions_status_created_at_index` (`status`,`created_at`),
  KEY `transactions_mobile_number_created_at_index` (`mobile_number`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

