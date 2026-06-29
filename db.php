<?php
/**
 * Turmeric Research Portal - Database Connection
 * Default configuration for XAMPP
 */

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "turmeric_db";

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("
    <div style='max-width: 600px; margin: 50px auto; padding: 30px; font-family: \"Segoe UI\", Roboto, sans-serif; background: #171923; border: 1px solid #ef4444; border-radius: 12px; color: #f7fafc; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);'>
        <h2 style='color: #ef4444; margin-top: 0;'>Database Connection Failed</h2>
        <p style='color: #a0aec0; line-height: 1.6;'>The portal was unable to connect to the MySQL database. Please check the following:</p>
        <ul style='color: #e2e8f0; line-height: 1.8; padding-left: 20px;'>
            <li>Ensure <strong>Apache</strong> and <strong>MySQL</strong> are started in your XAMPP Control Panel.</li>
            <li>Verify you have executed the <code style='background: #2d3748; padding: 2px 6px; border-radius: 4px; color: #f59e0b;'>setup_db.sql</code> script in phpMyAdmin.</li>
            <li>Confirm that the database is named <code style='background: #2d3748; padding: 2px 6px; border-radius: 4px; color: #f59e0b;'>turmeric_db</code>.</li>
        </ul>
        <div style='margin-top: 20px; padding: 10px; background: #2d3748; border-radius: 6px; font-family: monospace; font-size: 13px; color: #e2e8f0; border-left: 4px solid #ef4444;'>
            Error: " . mysqli_connect_error() . "
        </div>
        <button onclick='window.location.reload()' style='margin-top: 25px; padding: 10px 20px; background: #f59e0b; border: none; border-radius: 6px; color: #0d0e12; font-weight: bold; cursor: pointer; transition: background 0.2s;'>Retry Connection</button>
    </div>
    ");
}

// Set charset to support proper data encoding
mysqli_set_charset($conn, "utf8");
?>
