<?php
/**
 * Turmeric Research Portal - Delete Action Processor
 */

include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Secure delete using a prepared statement
    $stmt = mysqli_prepare($conn, "DELETE FROM turmeric_samples WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        // Redirect back to database list page on success
        header("Location: samples.php?status=success");
        exit();
    } else {
        mysqli_stmt_close($stmt);
        die("<div style='color: #ef4444; font-family: sans-serif; padding: 20px; background: #fef2f2; border: 1px solid #fee2e2; border-radius: 8px; margin: 20px;'>
                <strong>Failed to Delete Record:</strong> " . mysqli_error($conn) . "
             </div>");
    }
} else {
    // Redirect to list page if no ID was provided
    header("Location: samples.php");
    exit();
}
?>
