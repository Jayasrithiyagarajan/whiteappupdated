<?php
include_once(__DIR__ . '/../file/config.php');

if (!$conn || $conn->connect_error) {
    die("Database connection failed: " . ($conn ? $conn->connect_error : "No connection object"));
}

echo "Connected to database successfully.\n";

// 1. Ensure payment_mode column exists in project_info table
$checkCol = $conn->query("SHOW COLUMNS FROM project_info LIKE 'payment_mode'");
if ($checkCol && $checkCol->num_rows == 0) {
    $alterSql = "ALTER TABLE project_info ADD COLUMN payment_mode VARCHAR(50) DEFAULT 'Credit'";
    if ($conn->query($alterSql)) {
        echo "Added 'payment_mode' column to 'project_info' table successfully.\n";
    } else {
        echo "Error adding 'payment_mode' column: " . $conn->error . "\n";
    }
} else {
    echo "'payment_mode' column already exists in 'project_info' table.\n";
}

// 2. Ensure payment_list table exists
$createPaymentListSql = "CREATE TABLE IF NOT EXISTS payment_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_no VARCHAR(100) NOT NULL UNIQUE,
    inspector_name VARCHAR(255) NOT NULL,
    no_of_equipment INT DEFAULT 0,
    total_amount DECIMAL(10,2) DEFAULT 0.00,
    travel_charge DECIMAL(10,2) DEFAULT 0.00,
    broucher_no VARCHAR(100) DEFAULT NULL,
    remarks VARCHAR(50) DEFAULT 'Self',
    other_inspector_name VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($createPaymentListSql)) {
    echo "Table 'payment_list' created or verified successfully.\n";
} else {
    echo "Error creating 'payment_list' table: " . $conn->error . "\n";
}

echo "Setup completed successfully!\n";
