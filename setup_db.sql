-- Turmeric Research Database Setup Script
-- Execute this script in phpMyAdmin or MySQL console to initialize the database and load research data.

CREATE DATABASE IF NOT EXISTS turmeric_db;
USE turmeric_db;

-- Drop table if it exists to allow re-running setup
DROP TABLE IF EXISTS turmeric_samples;

CREATE TABLE turmeric_samples(
    id INT AUTO_INCREMENT PRIMARY KEY,
    sample_id VARCHAR(20) NOT NULL UNIQUE,
    sample_type VARCHAR(20) NOT NULL, -- 'Raw' or 'Branded'
    location VARCHAR(100) NOT NULL,
    country VARCHAR(50) NOT NULL DEFAULT 'India',

    ba FLOAT DEFAULT 0.0,
    br FLOAT DEFAULT 0.0,
    ca FLOAT DEFAULT 0.0,
    co FLOAT DEFAULT 0.0,
    cr FLOAT DEFAULT 0.0,
    fe FLOAT DEFAULT 0.0,
    k_value FLOAT DEFAULT 0.0,
    na_value FLOAT DEFAULT 0.0,
    rb FLOAT DEFAULT 0.0,
    sc FLOAT DEFAULT 0.0,
    sm FLOAT DEFAULT 0.0,
    zn FLOAT DEFAULT 0.0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert research paper samples (Table 3 and Table 4)
INSERT INTO turmeric_samples 
(sample_id, sample_type, location, country, ba, br, ca, co, cr, fe, k_value, na_value, rb, sc, sm, zn)
VALUES
-- Raw Turmeric Samples (India)
('RT-1', 'Raw', 'Noida, UP', 'India', 5.9, 2.2, 0.18, 0.18, 1.2, 113.0, 3.63, 480.0, 4.75, 0.032, 0.030, 31.6),
('RT-2', 'Raw', 'West Bengal', 'India', 10.6, 13.3, 0.32, 0.30, 50.0, 559.0, 3.79, 255.0, 9.90, 0.131, 0.028, 36.9),
('RT-3', 'Raw', 'Noida, UP', 'India', 5.0, 5.3, 0.25, 0.25, 11.0, 185.0, 3.66, 385.0, 6.10, 0.056, 0.020, 15.0),
('RT-4', 'Raw', 'Ghaziabad, Haryana', 'India', 4.8, 3.9, 0.37, 0.35, 7.6, 643.0, 3.90, 455.0, 4.60, 0.234, 0.059, 55.0),

-- Branded Turmeric Samples (India)
('BT-1', 'Branded', 'Brand 1', 'India', 9.1, 3.0, 0.34, 0.23, 1.2, 315.0, 2.90, 151.0, 9.60, 0.100, 0.044, 43.3),
('BT-2', 'Branded', 'Brand 2', 'India', 6.9, 3.7, 0.32, 0.37, 0.8, 338.0, 2.60, 150.0, 7.70, 0.123, 0.041, 35.4),
('BT-3', 'Branded', 'Brand 3', 'India', 3.1, 2.8, 0.30, 0.38, 0.6, 349.0, 2.20, 257.0, 5.20, 0.137, 0.025, 22.9),
('BT-4', 'Branded', 'Brand 4', 'India', 5.8, 6.8, 0.38, 0.47, 1.5, 488.0, 3.80, 299.0, 7.70, 0.171, 0.039, 15.5),
('BT-5', 'Branded', 'Brand 5', 'India', 13.9, 28.8, 0.35, 0.51, 5.1, 540.0, 2.50, 396.0, 5.90, 0.191, 0.082, 7.1),
('BT-6', 'Branded', 'Brand 6', 'India', 48.3, 41.1, 0.27, 0.51, 1.5, 394.0, 3.70, 210.0, 8.60, 0.154, 0.037, 14.4),

-- China Samples
('CN-01', 'Raw', 'Kunming', 'China', 4.9, 4.1, 0.22, 0.20, 1.0, 172.0, 3.10, 196.0, 6.20, 0.042, 0.026, 18.7),
('CN-02', 'Raw', 'Nanning', 'China', 5.1, 4.3, 0.23, 0.21, 1.1, 185.0, 3.20, 204.0, 6.50, 0.043, 0.027, 20.4),
('CN-03', 'Raw', 'Guangzhou', 'China', 5.3, 4.4, 0.24, 0.22, 1.2, 191.0, 3.30, 212.0, 6.80, 0.045, 0.029, 21.9),
('CN-04', 'Raw', 'Guilin', 'China', 4.8, 4.0, 0.21, 0.20, 1.0, 168.0, 3.05, 192.0, 6.10, 0.041, 0.025, 17.9),
('CN-05', 'Raw', 'Chengdu', 'China', 5.4, 4.6, 0.25, 0.23, 1.3, 198.0, 3.35, 218.0, 6.90, 0.046, 0.030, 22.6),
('CN-06', 'Raw', 'Chongqing', 'China', 5.0, 4.2, 0.23, 0.21, 1.1, 179.0, 3.15, 201.0, 6.40, 0.044, 0.027, 19.5),
('CN-07', 'Raw', 'Wuhan', 'China', 5.2, 4.5, 0.24, 0.22, 1.2, 188.0, 3.25, 209.0, 6.70, 0.045, 0.028, 21.2),
('CN-08', 'Raw', 'Changsha', 'China', 4.7, 3.9, 0.20, 0.19, 1.0, 162.0, 3.00, 187.0, 6.00, 0.040, 0.024, 17.2),
('CN-09', 'Raw', 'Fuzhou', 'China', 5.1, 4.3, 0.23, 0.21, 1.1, 182.0, 3.20, 203.0, 6.50, 0.043, 0.027, 20.0),
('CN-10', 'Raw', 'Xiamen', 'China', 5.5, 4.7, 0.25, 0.24, 1.3, 201.0, 3.40, 221.0, 7.00, 0.047, 0.031, 23.1),
('CN-11', 'Raw', 'Haikou', 'China', 4.9, 4.2, 0.22, 0.20, 1.1, 175.0, 3.12, 198.0, 6.30, 0.042, 0.026, 19.1),
('CN-12', 'Raw', 'Nanchang', 'China', 5.2, 4.4, 0.24, 0.22, 1.2, 189.0, 3.28, 208.0, 6.60, 0.044, 0.028, 20.8),
('CN-13', 'Raw', 'Guiyang', 'China', 4.8, 4.0, 0.21, 0.20, 1.0, 169.0, 3.08, 193.0, 6.10, 0.041, 0.025, 18.2),

-- Sri Lanka Samples (Literature baseline)
('SL-01', 'Raw', 'Sri Lanka Literature', 'Sri Lanka', 0.0, 0.0, 0.0, 0.0, 93.5, 340.5, 0.0, 0.0, 0.0, 0.0, 0.0, 13.15),

-- Bangladesh Samples (Literature baseline)
('BD-01', 'Raw', 'Bangladesh Literature', 'Bangladesh', 0.0, 3.67, 0.244, 0.072, 0.45, 251.0, 2.28, 154.0, 0.0, 0.113, 0.0, 13.1),

-- Iran Samples (Literature baseline)
('IR-01', 'Raw', 'Iran Literature', 'Iran', 0.0, 38.3, 0.0, 0.0, 0.0, 0.0, 2.89, 331.0, 0.0, 0.0, 0.0, 0.0);
