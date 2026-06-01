-- ====================================================================
-- Database Migration: create_prediction_history
-- Description: Creates the prediction_history table to store logs
--              of crop price predictions.
-- Target Database: agriconnect
-- ====================================================================

CREATE TABLE IF NOT EXISTS `prediction_history` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `crop_name` VARCHAR(100) NOT NULL,
  `demand` INT NOT NULL COMMENT 'Market demand score (1-100)',
  `supply` INT NOT NULL COMMENT 'Market supply score (1-100)',
  `season` VARCHAR(50) NOT NULL COMMENT 'Growth season (Kharif, Rabi, Summer, Whole Year)',
  `rainfall` FLOAT NOT NULL COMMENT 'Average seasonal rainfall in mm',
  `temperature` FLOAT NOT NULL COMMENT 'Average seasonal temperature in °C',
  `current_price` FLOAT NOT NULL COMMENT 'Benchmark current market price in ₹',
  `predicted_price` FLOAT NOT NULL COMMENT 'ML model predicted future price in ₹',
  `percentage_change` FLOAT NOT NULL COMMENT 'Calculated percentage price variance',
  `best_time_to_sell` VARCHAR(100) NOT NULL COMMENT 'Suggested best time to sell the crop',
  `model_used` VARCHAR(100) NOT NULL COMMENT 'Name of the ML model algorithm used',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Indexing for high-performance retrieval and search queries
CREATE INDEX idx_prediction_crop ON `prediction_history` (`crop_name`);
CREATE INDEX idx_prediction_created_at ON `prediction_history` (`created_at`);
