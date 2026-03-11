<?php

// Include database connection
include 'db.php';

// Get form data
$amount = $_POST['amount'];
$category = $_POST['category'];
$description = $_POST['description'];
$date = $_POST['date'];

// Prepare SQL query
$stmt = $conn->prepare("INSERT INTO expenses (amount, category, description, date) VALUES (?, ?, ?, ?)");

// Bind parameters
$stmt->bind_param("dsss", $amount, $category, $description, $date);

// Execute query
if ($stmt->execute()) {
    header("Location: view_expenses.php");
} else {
    echo "Error: " . $stmt->error;
}

// Close statement
$stmt->close();
$conn->close();

?>