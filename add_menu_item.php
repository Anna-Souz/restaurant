<?php
session_start();
require 'db.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $rate = $_POST['rate'];

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO menu (item_name, rate) VALUES (:item_name, :rate)");
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':rate', $rate);

    try {
        $stmt->execute();
        echo "Menu item added successfully! <a href='add_menu_item.php'>Add another item</a> | <a href='menu.php'>View menu</a>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Menu Item</title>
</head>
<body>
    <h2>Add Menu Item</h2>
    <form method="POST" action="add_menu_item.php">
        <label for="item_name">Item Name:</label>
        <input type="text" name="item_name" required><br>
        <label for="rate">Rate:</label>
        <input type="number" step="0.01" name="rate" required><br>
        <button type="submit">Add Item</button>
    </form>
</body>
</html>
